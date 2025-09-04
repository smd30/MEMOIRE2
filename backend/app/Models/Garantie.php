<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garantie extends Model
{
    use HasFactory;

    protected $table = 'garanties';

    protected $fillable = [
        'nom',
        'description',
        'compagnie_id',
        'obligatoire',
        'tarification_type',
        'tarification_config',
        'statut'
    ];

    protected $casts = [
        'obligatoire' => 'boolean',
        'tarification_config' => 'array'
    ];

    // Relations selon le diagramme UML
    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function garantieContrats()
    {
        return $this->hasMany(GarantieContrat::class);
    }

    // Types de tarification
    const TARIFICATION_FIXE = 'fixe';
    const TARIFICATION_POURCENTAGE = 'pourcentage';
    const TARIFICATION_FORFAIT = 'forfait';

    // Statuts
    const STATUT_ACTIVE = 'active';
    const STATUT_INACTIVE = 'inactive';

    // Garanties obligatoires
    const GARANTIE_RC = 'responsabilite_civile';

    // Scopes utiles
    public function scopeActive($query)
    {
        return $query->where('statut', self::STATUT_ACTIVE);
    }

    public function scopeObligatoires($query)
    {
        return $query->where('obligatoire', true);
    }

    public function scopeOptionnelles($query)
    {
        return $query->where('obligatoire', false);
    }

    public function scopeByCompagnie($query, $compagnieId)
    {
        return $query->where('compagnie_id', $compagnieId);
    }

    // Méthodes utilitaires
    public function estObligatoire()
    {
        return $this->obligatoire;
    }

    public function estOptionnelle()
    {
        return !$this->obligatoire;
    }

    public function getTarificationTypeFormate()
    {
        $types = [
            self::TARIFICATION_FIXE => 'Montant fixe',
            self::TARIFICATION_POURCENTAGE => 'Pourcentage de la valeur',
            self::TARIFICATION_FORFAIT => 'Forfait'
        ];

        return $types[$this->tarification_type] ?? 'Inconnu';
    }

    // Calcul du montant selon le type de tarification
    public function calculerMontant($valeurVehicule = null, $puissanceFiscale = null)
    {
        switch ($this->tarification_type) {
            case self::TARIFICATION_FIXE:
                return $this->tarification_config['montant'] ?? 0;
            
            case self::TARIFICATION_POURCENTAGE:
                if (!$valeurVehicule) return 0;
                $taux = $this->tarification_config['taux'] ?? 0;
                return $valeurVehicule * $taux;
            
            case self::TARIFICATION_FORFAIT:
                return $this->tarification_config['forfait'] ?? 0;
            
            default:
                return 0;
        }
    }

    // Vérifier si c'est la garantie RC
    public function estResponsabiliteCivile()
    {
        return $this->nom === 'Responsabilité Civile' || 
               $this->nom === self::GARANTIE_RC;
    }
}
