<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sinistre extends Model
{
    use HasFactory;

    protected $table = 'sinistres';

    protected $fillable = [
        'sinistre_number',
        'contract_id',
        'user_id',
        'managed_by',
        'type',
        'incident_date',
        'location',
        'description',
        'estimated_damage',
        'status',
        'manager_notes',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'estimated_damage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    // Scopes
    public function scopeNouveau($query)
    {
        return $query->where('status', 'nouveau');
    }

    public function scopeEnCours($query)
    {
        return $query->where('status', 'en_cours');
    }

    public function scopeValide($query)
    {
        return $query->where('status', 'valide');
    }

    public function scopeRejete($query)
    {
        return $query->where('status', 'rejete');
    }

    // Méthodes
    public function isNouveau(): bool
    {
        return $this->status === 'nouveau';
    }

    public function isEnCours(): bool
    {
        return $this->status === 'en_cours';
    }

    public function isValide(): bool
    {
        return $this->status === 'valide';
    }

    public function isRejete(): bool
    {
        return $this->status === 'rejete';
    }

    public function getStatutColor(): string
    {
        return match($this->status) {
            'nouveau' => 'warning',
            'en_cours' => 'info',
            'valide' => 'success',
            'rejete' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatutText(): string
    {
        return match($this->status) {
            'nouveau' => 'Nouveau',
            'en_cours' => 'En cours',
            'valide' => 'Validé',
            'rejete' => 'Rejeté',
            default => 'Inconnu'
        };
    }

    public function getTypeText(): string
    {
        return match($this->type) {
            'collision' => 'Collision',
            'vol' => 'Vol',
            'incendie' => 'Incendie',
            'bris' => 'Bris',
            'autre' => 'Autre',
            default => 'Inconnu'
        };
    }

    // Accesseur pour sinistre_number (pour compatibilité avec le frontend)
    public function getSinistreNumberAttribute(): string
    {
        return 'SIN-' . date('Y', strtotime($this->created_at)) . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    // Accesseurs pour compatibilité avec le frontend
    public function getDateSinistreAttribute(): string
    {
        return $this->incident_date;
    }

    public function getLieuSinistreAttribute(): string
    {
        return $this->location;
    }

    public function getDescriptionSinistreAttribute(): string
    {
        return $this->description;
    }

    public function getTypeSinistreAttribute(): string
    {
        return $this->type;
    }

    public function getMontantEstimeAttribute(): float
    {
        return $this->estimated_damage;
    }

    public function getStatutAttribute(): string
    {
        return $this->status;
    }

    public function getNotesExpertAttribute(): string
    {
        return $this->manager_notes;
    }
}
