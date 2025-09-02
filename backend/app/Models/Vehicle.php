<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'marqueVehicule',
        'modèle',
        'immatriculation',
        'categorie',
        'puissanceFiscale',
        'dateMiseEnCirculation',
        'valeurVéhicule',
        'carteGrise',
        'color',
    ];

    protected $casts = [
        'puissanceFiscale' => 'integer',
        'valeurVéhicule' => 'decimal:2',
        'dateMiseEnCirculation' => 'date',
    ];

    /**
     * Relation avec l'utilisateur propriétaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les contrats
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('categorie', $category);
    }

    /**
     * Scope pour une marque spécifique
     */
    public function scopeByBrand($query, $brand)
    {
        return $query->where('marqueVehicule', $brand);
    }

    /**
     * Scope pour une puissance fiscale spécifique
     */
    public function scopeByPowerFiscal($query, $powerFiscal)
    {
        return $query->where('puissanceFiscale', $powerFiscal);
    }

    /**
     * Obtenir l'âge du véhicule
     */
    public function getAge(): int
    {
        return $this->dateMiseEnCirculation->diffInYears(now());
    }

    /**
     * Vérifier si le véhicule est ancien (plus de 25 ans)
     */
    public function isOld(): bool
    {
        return $this->getAge() > 25;
    }

    /**
     * Obtenir la description complète du véhicule
     */
    public function getFullDescription(): string
    {
        return "{$this->marqueVehicule} {$this->modèle} ({$this->immatriculation})";
    }

    /**
     * Obtenir la couleur
     */
    public function getColor(): string
    {
        return $this->color ?? 'Non spécifiée';
    }

    /**
     * Obtenir le contrat actif
     */
    public function getActiveContract(): ?Contract
    {
        return $this->contracts()
            ->where('status', 'actif')
            ->where('end_date', '>', now())
            ->first();
    }

    /**
     * Vérifier si le véhicule a un contrat actif
     */
    public function hasActiveContract(): bool
    {
        return $this->getActiveContract() !== null;
    }

    /**
     * Obtenir le contrat expirant bientôt (dans les 30 jours)
     */
    public function getExpiringContract(): ?Contract
    {
        return $this->contracts()
            ->where('status', 'actif')
            ->where('end_date', '>', now())
            ->where('end_date', '<=', now()->addDays(30))
            ->first();
    }

    /**
     * Vérifier si le véhicule a un contrat qui expire bientôt
     */
    public function hasExpiringContract(): bool
    {
        return $this->getExpiringContract() !== null;
    }

    /**
     * Validation des règles métier
     */
    public static function getValidationRules(): array
    {
        return [
            'marqueVehicule' => 'required|string|max:100',
            'modèle' => 'required|string|max:100',
            'immatriculation' => 'required|string|unique:vehicles,immatriculation|regex:/^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$/',
            'categorie' => 'required|string|in:Citadine,SUV,Berline,Utilitaire,Moto',
            'puissanceFiscale' => 'required|integer|min:1|max:50',
            'dateMiseEnCirculation' => 'required|date|before_or_equal:today',
            'valeurVéhicule' => 'required|numeric|min:0',
            'carteGrise' => 'required|string|max:50',
            'color' => 'required|string|max:50',
        ];
    }
}
