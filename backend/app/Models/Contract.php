<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'duration_months',
        'base_premium',
        'total_premium',
        'taxes',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_premium' => 'decimal:2',
        'total_premium' => 'decimal:2',
        'taxes' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le véhicule
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }



    /**
     * Relation avec les garanties (many-to-many)
     */
    public function garanties(): BelongsToMany
    {
        return $this->belongsToMany(Garantie::class, 'contract_garanties')
                    ->withPivot('coefficient', 'premium')
                    ->withTimestamps();
    }

    /**
     * Scope pour les contrats actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour les contrats expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope pour les contrats résiliés
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Vérifier si le contrat est actif
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Vérifier si le contrat est expiré
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->end_date < now();
    }

    /**
     * Vérifier si le contrat peut être renouvelé
     */
    public function canBeRenewed(): bool
    {
        return $this->isActive() && $this->end_date->diffInDays(now(), false) <= 30;
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public function getDaysRemaining(): int
    {
        return $this->end_date->diffInDays(now(), false);
    }

    /**
     * Obtenir le montant total des garanties
     */
    public function getGarantiesTotal(): float
    {
        return $this->garanties->sum(function($garantie) {
            return $garantie->pivot->premium ?? 0;
        });
    }

    /**
     * Générer le numéro de contrat
     */
    public static function generateContractNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'CTR-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Obtenir le montant mensuel
     */
    public function getMontantMensuel(): float
    {
        return $this->total_premium / $this->duration_months;
    }
}
