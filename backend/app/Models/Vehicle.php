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
        'plate_number',
        'brand',
        'model',
        'year',
        'power_fiscal',
        'category',
        'sub_category',
        'fuel_type',
        'color',
        'mileage',
        'additional_features',
        'is_active',
    ];

    protected $casts = [
        'year' => 'integer',
        'power_fiscal' => 'integer',
        'mileage' => 'integer',
        'additional_features' => 'array',
        'is_active' => 'boolean',
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
     * Scope pour les véhicules actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour une marque spécifique
     */
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope pour une puissance fiscale spécifique
     */
    public function scopeByPowerFiscal($query, $powerFiscal)
    {
        return $query->where('power_fiscal', $powerFiscal);
    }

    /**
     * Vérifier si le véhicule est actif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activer le véhicule
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver le véhicule
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Obtenir l'âge du véhicule
     */
    public function getAge(): int
    {
        return date('Y') - $this->year;
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
        return "{$this->brand} {$this->model} {$this->year} ({$this->plate_number})";
    }

    /**
     * Obtenir le type de carburant
     */
    public function getFuelType(): string
    {
        return $this->fuel_type ?? 'Non spécifié';
    }

    /**
     * Obtenir la couleur
     */
    public function getColor(): string
    {
        return $this->color ?? 'Non spécifiée';
    }

    /**
     * Obtenir le kilométrage
     */
    public function getMileage(): int
    {
        return $this->mileage ?? 0;
    }

    /**
     * Obtenir les caractéristiques additionnelles
     */
    public function getAdditionalFeatures(): array
    {
        return $this->additional_features ?? [];
    }

    /**
     * Vérifier si le véhicule a une caractéristique spécifique
     */
    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->getAdditionalFeatures());
    }

    /**
     * Obtenir le contrat actif
     */
    public function getActiveContract(): ?Contract
    {
        return $this->contracts()
            ->where('status', 'active')
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
            ->where('status', 'active')
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
            'plate_number' => 'required|string|unique:vehicles,plate_number|regex:/^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$/',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'power_fiscal' => 'required|integer|min:1|max:50',
            'category' => 'required|string|in:Citadine,SUV,Berline,Utilitaire,Moto',
            'sub_category' => 'nullable|string|max:100',
            'fuel_type' => 'required|string|in:Essence,Diesel,Électrique,Hybride,GPL',
            'color' => 'required|string|max:50',
            'mileage' => 'required|integer|min:0',
            'additional_features' => 'nullable|array',
        ];
    }
}
