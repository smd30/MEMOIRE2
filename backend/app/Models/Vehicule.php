<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $table = 'vehicules';

    protected $fillable = [
        'marque_vehicule',
        'modele',
        'immatriculation',
        'categorie',
        'puissance_fiscale',
        'date_mise_en_circulation',
        'valeur_vehicule',
        'valeur_venale',
        'carte_grise',
        'numero_chassis',
        'energie',
        'places',
        'age_vehicule',
        'proprietaire_nom',
        'proprietaire_prenom',
        'proprietaire_adresse',
        'proprietaire_telephone',
        'proprietaire_email',
        'user_id'
    ];

    protected $casts = [
        'date_mise_en_circulation' => 'date',
        'valeur_vehicule' => 'decimal:2',
        'valeur_venale' => 'decimal:2',
        'puissance_fiscale' => 'integer',
        'places' => 'integer',
        'age_vehicule' => 'integer'
    ];

    // Relations selon le diagramme UML
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    // Catégories de véhicules au Sénégal
    const CATEGORIES = [
        'voiture_particuliere' => 'Voiture particulière',
        'utilitaire_leger' => 'Utilitaire léger',
        'transport_commun' => 'Transport en commun',
        'poids_lourd' => 'Poids lourd',
        'moto' => 'Motocycle',
        'vehicule_special' => 'Véhicule spécial',
        'vehicule_administratif' => 'Véhicule administratif'
    ];

    // Types d'énergie
    const ENERGIES = [
        'essence' => 'Essence',
        'diesel' => 'Diesel',
        'gaz' => 'Gaz',
        'electricite' => 'Électricité'
    ];

    // Périodes de police
    const PERIODES_POLICE = [
        1 => '1 mois',
        3 => '3 mois',
        6 => '6 mois',
        12 => '12 mois'
    ];

    // Scopes utiles
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeByEnergie($query, $energie)
    {
        return $query->where('energie', $energie);
    }

    // Méthodes utilitaires
    public function getCategorieFormateeAttribute()
    {
        return self::CATEGORIES[$this->categorie] ?? 'Inconnue';
    }

    public function getEnergieFormateeAttribute()
    {
        return self::ENERGIES[$this->energie] ?? 'Inconnue';
    }

    public function getValeurVehiculeFormateeAttribute()
    {
        return number_format($this->valeur_vehicule, 0, ',', ' ') . ' FCFA';
    }

    public function getValeurVenaleFormateeAttribute()
    {
        return number_format($this->valeur_venale, 0, ',', ' ') . ' FCFA';
    }

    public function getDateMiseEnCirculationFormateeAttribute()
    {
        return $this->date_mise_en_circulation ? $this->date_mise_en_circulation->format('d/m/Y') : 'Non renseignée';
    }

    public function getProprietaireCompletAttribute()
    {
        return $this->proprietaire_prenom . ' ' . $this->proprietaire_nom;
    }

    // Calcul de l'âge du véhicule
    public function calculerAgeVehicule()
    {
        if (!$this->date_mise_en_circulation) {
            return null;
        }

        return now()->diffInYears($this->date_mise_en_circulation);
    }

    // Déterminer la catégorie de puissance pour la tarification
    public function getCategoriePuissance()
    {
        $puissance = $this->puissance_fiscale;

        if ($puissance <= 3) return '3_cv';
        if ($puissance <= 7) return '4_7_cv';
        if ($puissance <= 12) return '8_12_cv';
        if ($puissance <= 17) return '13_17_cv';
        return '17_plus_cv';
    }

    // Vérifier si le véhicule est récent (moins de 5 ans)
    public function estVehiculeRecent()
    {
        $age = $this->calculerAgeVehicule();
        return $age !== null && $age <= 5;
    }

    // Vérifier si le véhicule est ancien (plus de 10 ans)
    public function estVehiculeAncien()
    {
        $age = $this->calculerAgeVehicule();
        return $age !== null && $age > 10;
    }

    // Obtenir le taux de dépréciation selon l'âge
    public function getTauxDepreciation()
    {
        $age = $this->calculerAgeVehicule();
        
        if ($age === null) return 1.0;
        
        // Dépréciation annuelle de 10%
        return max(0.3, 1 - ($age * 0.1));
    }

    // Calculer la valeur vénale estimée
    public function calculerValeurVenale()
    {
        if (!$this->valeur_vehicule) return 0;
        
        $taux = $this->getTauxDepreciation();
        return $this->valeur_vehicule * $taux;
    }

    // Validation des données
    public static function getReglesValidation()
    {
        return [
            'marque_vehicule' => 'required|string|max:100',
            'modele' => 'required|string|max:100',
            'immatriculation' => 'required|string|max:20|unique:vehicules,immatriculation',
            'categorie' => 'required|string|in:' . implode(',', array_keys(self::CATEGORIES)),
            'puissance_fiscale' => 'required|integer|min:1|max:50',
            'date_mise_en_circulation' => 'required|date|before_or_equal:today',
            'valeur_vehicule' => 'required|numeric|min:100000',
            'valeur_venale' => 'required|numeric|min:100000',
            'numero_chassis' => 'required|string|max:50|unique:vehicules,numero_chassis',
            'energie' => 'required|string|in:' . implode(',', array_keys(self::ENERGIES)),
            'places' => 'required|integer|min:1|max:50',
            'proprietaire_nom' => 'required|string|max:100',
            'proprietaire_prenom' => 'required|string|max:100',
            'proprietaire_adresse' => 'required|string|max:255',
            'proprietaire_telephone' => 'required|string|max:20',
            'proprietaire_email' => 'required|email|max:100',
            'carte_grise' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ];
    }

    // Messages d'erreur personnalisés
    public static function getMessagesValidation()
    {
        return [
            'immatriculation.unique' => 'Cette immatriculation est déjà enregistrée.',
            'numero_chassis.unique' => 'Ce numéro de chassis est déjà enregistré.',
            'date_mise_en_circulation.before_or_equal' => 'La date de mise en circulation ne peut pas être dans le futur.',
            'valeur_vehicule.min' => 'La valeur du véhicule doit être d\'au moins 100 000 FCFA.',
            'valeur_venale.min' => 'La valeur vénale doit être d\'au moins 100 000 FCFA.',
            'carte_grise.mimes' => 'La carte grise doit être au format PDF, JPG, JPEG ou PNG.',
            'carte_grise.max' => 'La carte grise ne doit pas dépasser 2MB.'
        ];
    }

    // Boot method pour calculer automatiquement l'âge et la valeur vénale
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vehicule) {
            $vehicule->age_vehicule = $vehicule->calculerAgeVehicule();
            if (!$vehicule->valeur_venale) {
                $vehicule->valeur_venale = $vehicule->calculerValeurVenale();
            }
        });

        static::updating(function ($vehicule) {
            $vehicule->age_vehicule = $vehicule->calculerAgeVehicule();
        });
    }
}





