<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quote_number',
        'vehicle_info',
        'selected_garanties',
        'duration_months',
        'base_premium',
        'total_premium',
        'taxes',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'vehicle_info' => 'array',
        'selected_garanties' => 'array',
        'base_premium' => 'decimal:2',
        'total_premium' => 'decimal:2',
        'taxes' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les devis actifs (non expirés)
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope pour les devis expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope pour un statut spécifique
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Vérifier si le devis est expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Vérifier si le devis est actif
     */
    public function isActive(): bool
    {
        return !$this->isExpired() && $this->status !== 'expired';
    }

    /**
     * Vérifier si le devis peut être accepté
     */
    public function canBeAccepted(): bool
    {
        return $this->isActive() && $this->status === 'sent';
    }

    /**
     * Marquer le devis comme accepté
     */
    public function accept(): void
    {
        $this->update(['status' => 'accepted']);
    }

    /**
     * Marquer le devis comme expiré
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Obtenir les informations du véhicule
     */
    public function getVehicleInfo(): array
    {
        return $this->vehicle_info ?? [];
    }

    /**
     * Obtenir la marque du véhicule
     */
    public function getVehicleBrand(): ?string
    {
        return $this->vehicle_info['brand'] ?? null;
    }

    /**
     * Obtenir le modèle du véhicule
     */
    public function getVehicleModel(): ?string
    {
        return $this->vehicle_info['model'] ?? null;
    }

    /**
     * Obtenir la catégorie du véhicule
     */
    public function getVehicleCategory(): ?string
    {
        return $this->vehicle_info['category'] ?? null;
    }

    /**
     * Obtenir la puissance fiscale du véhicule
     */
    public function getVehiclePowerFiscal(): ?int
    {
        return $this->vehicle_info['power_fiscal'] ?? null;
    }

    /**
     * Obtenir l'année du véhicule
     */
    public function getVehicleYear(): ?int
    {
        return $this->vehicle_info['year'] ?? null;
    }

    /**
     * Obtenir la prime mensuelle
     */
    public function getMonthlyPremium(): float
    {
        return round($this->total_premium / $this->duration_months, 2);
    }

    /**
     * Obtenir le nombre de jours restants avant expiration
     */
    public function getDaysUntilExpiry(): int
    {
        return $this->expires_at->diffInDays(now(), false);
    }

    /**
     * Générer un nouveau numéro de devis
     */
    public static function generateQuoteNumber(): string
    {
        return 'CQ-' . date('YmdHis') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method pour générer automatiquement le numéro de devis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($devis) {
            if (empty($devis->quote_number)) {
                $devis->quote_number = static::generateQuoteNumber();
            }
        });
    }
}
