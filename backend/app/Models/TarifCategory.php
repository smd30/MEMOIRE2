<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sub_category',
        'power_fiscal_min',
        'power_fiscal_max',
        'base_rate_monthly',
        'coefficient_vol',
        'coefficient_incendie',
        'coefficient_bris',
        'coefficient_defense',
        'conditions',
        'is_active',
    ];

    protected $casts = [
        'power_fiscal_min' => 'integer',
        'power_fiscal_max' => 'integer',
        'base_rate_monthly' => 'decimal:2',
        'coefficient_vol' => 'decimal:2',
        'coefficient_incendie' => 'decimal:2',
        'coefficient_bris' => 'decimal:2',
        'coefficient_defense' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope pour les tarifs actifs
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
        return $query->where('name', $category);
    }

    /**
     * Scope pour une sous-catégorie spécifique
     */
    public function scopeBySubCategory($query, $subCategory)
    {
        return $query->where('sub_category', $subCategory);
    }

    /**
     * Scope pour une puissance fiscale spécifique
     */
    public function scopeByPowerFiscal($query, $powerFiscal)
    {
        return $query->where('power_fiscal_min', '<=', $powerFiscal)
                    ->where('power_fiscal_max', '>=', $powerFiscal);
    }

    /**
     * Vérifier si le tarif est actif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activer le tarif
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver le tarif
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Obtenir le coefficient pour une garantie spécifique
     */
    public function getCoefficient(string $garantieName): float
    {
        $coefficientMap = [
            'vol' => $this->coefficient_vol,
            'incendie' => $this->coefficient_incendie,
            'bris' => $this->coefficient_bris,
            'defense' => $this->coefficient_defense,
        ];

        return $coefficientMap[$garantieName] ?? 1.00;
    }

    /**
     * Calculer la prime de base pour une durée donnée
     */
    public function calculateBasePremium(int $durationMonths): float
    {
        return $this->base_rate_monthly * $durationMonths;
    }

    /**
     * Vérifier si ce tarif correspond à un véhicule
     */
    public function matchesVehicle(string $category, ?string $subCategory, int $powerFiscal): bool
    {
        return $this->name === $category &&
               ($this->sub_category === $subCategory || $this->sub_category === null) &&
               $this->power_fiscal_min <= $powerFiscal &&
               $this->power_fiscal_max >= $powerFiscal;
    }
}
