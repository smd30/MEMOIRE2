const { Pool } = require('pg');

class TarifService {
  constructor() {
    this.pool = new Pool({
      connectionString: process.env.DATABASE_URL,
      ssl: process.env.NODE_ENV === 'production' ? { rejectUnauthorized: false } : false
    });
  }

  /**
   * Calculer la prime de base selon la grille tarifaire
   */
  async calculateBasePremium(vehicleInfo, durationMonths) {
    try {
      const { category, sub_category, power_fiscal } = vehicleInfo;

      const query = `
        SELECT base_rate_monthly, conditions
        FROM tarif_categories 
        WHERE name = $1 
          AND (sub_category = $2 OR sub_category IS NULL)
          AND power_fiscal_min <= $3 
          AND power_fiscal_max >= $3
          AND is_active = true
        ORDER BY 
          CASE WHEN sub_category = $2 THEN 0 ELSE 1 END,
          power_fiscal_min ASC
        LIMIT 1
      `;

      const result = await this.pool.query(query, [category, sub_category, power_fiscal]);

      if (result.rows.length === 0) {
        console.warn(`Aucun tarif trouvé pour: ${category}/${sub_category}/${power_fiscal}`);
        
        // Tarif par défaut selon la catégorie
        const defaultRates = {
          'Citadine': 40.00,
          'SUV': 60.00,
          'Berline': 50.00,
          'Utilitaire': 55.00,
          'Moto': 30.00
        };

        const defaultRate = defaultRates[category] || 50.00;
        return defaultRate * durationMonths;
      }

      const tarif = result.rows[0];
      return tarif.base_rate_monthly * durationMonths;

    } catch (error) {
      console.error('Erreur lors du calcul de la prime de base:', error);
      throw new Error('Erreur lors du calcul de la prime');
    }
  }

  /**
   * Obtenir les coefficients des garanties
   */
  async getGarantieCoefficients(selectedGaranties) {
    try {
      const query = `
        SELECT name, coefficient, is_required
        FROM garanties 
        WHERE name = ANY($1) AND is_active = true
      `;

      const result = await this.pool.query(query, [selectedGaranties]);
      const coefficients = {};

      for (const garantie of result.rows) {
        coefficients[garantie.name] = garantie.coefficient;
      }

      // Ajouter des coefficients par défaut pour les garanties non trouvées
      const defaultCoefficients = {
        'vol': 1.2,
        'incendie': 0.8,
        'bris': 1.0,
        'defense': 1.1,
        'dommages': 1.5
      };

      for (const garantie of selectedGaranties) {
        if (!coefficients[garantie]) {
          coefficients[garantie] = defaultCoefficients[garantie] || 1.0;
          console.warn(`Coefficient par défaut utilisé pour la garantie: ${garantie}`);
        }
      }

      return coefficients;

    } catch (error) {
      console.error('Erreur lors de la récupération des coefficients:', error);
      
      // Retourner des coefficients par défaut en cas d'erreur
      const defaultCoefficients = {
        'vol': 1.2,
        'incendie': 0.8,
        'bris': 1.0,
        'defense': 1.1,
        'dommages': 1.5
      };

      const coefficients = {};
      for (const garantie of selectedGaranties) {
        coefficients[garantie] = defaultCoefficients[garantie] || 1.0;
      }

      return coefficients;
    }
  }

  /**
   * Obtenir la grille tarifaire complète
   */
  async getTarifGrid() {
    try {
      const query = `
        SELECT 
          name, 
          sub_category, 
          power_fiscal_min, 
          power_fiscal_max, 
          base_rate_monthly, 
          conditions,
          is_active
        FROM tarif_categories 
        WHERE is_active = true
        ORDER BY name, sub_category, power_fiscal_min
      `;

      const result = await this.pool.query(query);
      return result.rows;

    } catch (error) {
      console.error('Erreur lors de la récupération de la grille tarifaire:', error);
      throw new Error('Erreur lors de la récupération des tarifs');
    }
  }

  /**
   * Calculer la prime totale avec garanties et taxes
   */
  async calculateTotalPremium(vehicleInfo, selectedGaranties, durationMonths) {
    try {
      const basePremium = await this.calculateBasePremium(vehicleInfo, durationMonths);
      const garantieCoefficients = await this.getGarantieCoefficients(selectedGaranties);

      let garantiesPremium = 0;
      const garantiesDetails = [];

      for (const garantie of selectedGaranties) {
        const coefficient = garantieCoefficients[garantie] || 1.0;
        const premium = basePremium * coefficient;
        garantiesPremium += premium;
        
        garantiesDetails.push({
          name: garantie,
          coefficient: coefficient,
          premium: premium
        });
      }

      // Calculer les taxes (20% par défaut)
      const taxes = (basePremium + garantiesPremium) * 0.20;
      const totalPremium = basePremium + garantiesPremium + taxes;

      return {
        basePremium: Math.round(basePremium * 100) / 100,
        garantiesPremium: Math.round(garantiesPremium * 100) / 100,
        taxes: Math.round(taxes * 100) / 100,
        totalPremium: Math.round(totalPremium * 100) / 100,
        garantiesDetails: garantiesDetails
      };

    } catch (error) {
      console.error('Erreur lors du calcul de la prime totale:', error);
      throw new Error('Erreur lors du calcul de la prime');
    }
  }

  /**
   * Vérifier si un véhicule est éligible selon les tarifs
   */
  async checkVehicleEligibility(vehicleInfo) {
    try {
      const { category, sub_category, power_fiscal, year } = vehicleInfo;

      // Vérifications de base
      const currentYear = new Date().getFullYear();
      const vehicleAge = currentYear - year;

      // Vérifier l'âge du véhicule
      if (vehicleAge > 25) {
        return {
          eligible: false,
          reason: 'Véhicule trop ancien (plus de 25 ans)',
          code: 'VEHICLE_TOO_OLD'
        };
      }

      // Vérifier la puissance fiscale
      if (power_fiscal < 1 || power_fiscal > 50) {
        return {
          eligible: false,
          reason: 'Puissance fiscale non valide',
          code: 'INVALID_POWER_FISCAL'
        };
      }

      // Vérifier si un tarif existe pour cette catégorie
      const tarifExists = await this.tarifExists(category, sub_category, power_fiscal);
      
      if (!tarifExists) {
        return {
          eligible: false,
          reason: 'Aucun tarif disponible pour cette catégorie de véhicule',
          code: 'NO_TARIF_AVAILABLE'
        };
      }

      return {
        eligible: true,
        reason: 'Véhicule éligible',
        code: 'ELIGIBLE'
      };

    } catch (error) {
      console.error('Erreur lors de la vérification d\'éligibilité:', error);
      return {
        eligible: false,
        reason: 'Erreur lors de la vérification',
        code: 'ERROR'
      };
    }
  }

  /**
   * Vérifier si un tarif existe pour une catégorie donnée
   */
  async tarifExists(category, sub_category, power_fiscal) {
    try {
      const query = `
        SELECT COUNT(*) as count
        FROM tarif_categories 
        WHERE name = $1 
          AND (sub_category = $2 OR sub_category IS NULL)
          AND power_fiscal_min <= $3 
          AND power_fiscal_max >= $3
          AND is_active = true
      `;

      const result = await this.pool.query(query, [category, sub_category, power_fiscal]);
      return result.rows[0].count > 0;

    } catch (error) {
      console.error('Erreur lors de la vérification d\'existence du tarif:', error);
      return false;
    }
  }

  /**
   * Fermer la connexion à la base de données
   */
  async close() {
    await this.pool.end();
  }
}

module.exports = TarifService;

