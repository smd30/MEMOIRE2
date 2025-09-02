const express = require('express');
const router = express.Router();
const { validateQuoteRequest } = require('../middleware/validation');
const QuoteService = require('../services/QuoteService');
const TarifService = require('../services/TarifService');

const quoteService = new QuoteService();
const tarifService = new TarifService();

/**
 * POST /api/quote
 * Calculer un devis d'assurance
 */
router.post('/', validateQuoteRequest, async (req, res, next) => {
  try {
    const {
      vehicle_info,
      selected_garanties,
      duration_months,
      client_info
    } = req.body;

    // Vérifier l'éligibilité du véhicule
    const eligibility = await quoteService.checkEligibility(vehicle_info);
    
    if (!eligibility.eligible) {
      return res.status(400).json({
        success: false,
        message: 'Véhicule non éligible',
        reason: eligibility.reason
      });
    }

    // Calculer la prime selon la grille tarifaire
    const basePremium = await tarifService.calculateBasePremium(
      vehicle_info,
      duration_months
    );

    // Calculer les coefficients des garanties
    const garantieCoefficients = await tarifService.getGarantieCoefficients(
      selected_garanties
    );

    // Calculer la prime totale
    let garantiesPremium = 0;
    const garantiesDetails = [];

    for (const garantie of selected_garanties) {
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

    // Générer un numéro de devis unique
    const quoteNumber = `CQ-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

    // Créer la réponse du devis
    const quote = {
      quote_number: quoteNumber,
      vehicle_info: vehicle_info,
      selected_garanties: selected_garanties,
      duration_months: duration_months,
      calculation: {
        base_premium: Math.round(basePremium * 100) / 100,
        garanties_premium: Math.round(garantiesPremium * 100) / 100,
        taxes: Math.round(taxes * 100) / 100,
        total_premium: Math.round(totalPremium * 100) / 100,
        monthly_premium: Math.round(totalPremium / duration_months * 100) / 100
      },
      garanties_details: garantiesDetails,
      eligibility: eligibility,
      expires_at: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000), // 30 jours
      created_at: new Date()
    };

    // Enregistrer le devis en base (optionnel)
    await quoteService.saveQuote(quote, client_info);

    res.json({
      success: true,
      message: 'Devis calculé avec succès',
      data: quote
    });

  } catch (error) {
    console.error('Erreur lors du calcul du devis:', error);
    next(error);
  }
});

/**
 * GET /api/quote/:quoteNumber
 * Récupérer un devis existant
 */
router.get('/:quoteNumber', async (req, res, next) => {
  try {
    const { quoteNumber } = req.params;
    
    const quote = await quoteService.getQuote(quoteNumber);
    
    if (!quote) {
      return res.status(404).json({
        success: false,
        message: 'Devis non trouvé'
      });
    }

    res.json({
      success: true,
      data: quote
    });

  } catch (error) {
    console.error('Erreur lors de la récupération du devis:', error);
    next(error);
  }
});

/**
 * POST /api/quote/validate
 * Valider un devis (vérification complète)
 */
router.post('/validate', validateQuoteRequest, async (req, res, next) => {
  try {
    const {
      vehicle_info,
      selected_garanties,
      duration_months,
      client_info
    } = req.body;

    // Vérifications complètes
    const validations = await quoteService.validateQuote({
      vehicle_info,
      selected_garanties,
      duration_months,
      client_info
    });

    if (!validations.isValid) {
      return res.status(400).json({
        success: false,
        message: 'Devis non valide',
        errors: validations.errors
      });
    }

    res.json({
      success: true,
      message: 'Devis validé avec succès',
      data: {
        isValid: true,
        warnings: validations.warnings || []
      }
    });

  } catch (error) {
    console.error('Erreur lors de la validation du devis:', error);
    next(error);
  }
});

module.exports = router;

