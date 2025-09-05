<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'insurance_contracts';

    protected $fillable = [
        'user_id',
        'vehicule_id',
        'compagnie_id',
        'numero_police',
        'numero_attestation',
        'cle_securite',
        'date_debut',
        'date_fin',
        'periode_police',
        'garanties_selectionnees',
        'prime_rc',
        'garanties_optionnelles',
        'accessoires_police',
        'prime_nette',
        'taxes_tuca',
        'prime_ttc',
        'statut',
        'date_souscription',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_souscription' => 'datetime',
        'garanties_selectionnees' => 'array',
        'prime_rc' => 'decimal:2',
        'garanties_optionnelles' => 'decimal:2',
        'accessoires_police' => 'decimal:2',
        'prime_nette' => 'decimal:2',
        'taxes_tuca' => 'decimal:2',
        'prime_ttc' => 'decimal:2',
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
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Relation avec la compagnie
     */
    public function compagnie(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class);
    }

    /**
     * Scope pour les contrats actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les contrats expirés
     */
    public function scopeExpire($query)
    {
        return $query->where('statut', 'expire');
    }

    /**
     * Scope pour les contrats résiliés
     */
    public function scopeResilie($query)
    {
        return $query->where('statut', 'resilie');
    }

    /**
     * Vérifier si le contrat est actif
     */
    public function estActif(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifier si le contrat est expiré
     */
    public function estExpire(): bool
    {
        return $this->statut === 'expire' || $this->date_fin->isPast();
    }

    /**
     * Vérifier si le contrat est résilié
     */
    public function estResilie(): bool
    {
        return $this->statut === 'resilie';
    }

    /**
     * Obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return match($this->statut) {
            'actif' => 'Actif',
            'expire' => 'Expiré',
            'resilie' => 'Résilié',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir la durée restante en jours
     */
    public function getJoursRestantsAttribute(): int
    {
        return now()->diffInDays($this->date_fin, false);
    }

    /**
     * Obtenir le pourcentage de progression
     */
    public function getProgressionAttribute(): float
    {
        $total = $this->date_debut->diffInDays($this->date_fin);
        $ecoule = $this->date_debut->diffInDays(now());
        
        return min(100, max(0, ($ecoule / $total) * 100));
    }
}
