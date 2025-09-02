<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Garantie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'coefficient',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les contrats (many-to-many)
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contract_garanties')
                    ->withPivot('coefficient', 'premium')
                    ->withTimestamps();
    }

    /**
     * Scope pour les garanties actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les garanties requises
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Vérifier si la garantie est requise
     */
    public function isRequired(): bool
    {
        return $this->is_required;
    }

    /**
     * Vérifier si la garantie est active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activer la garantie
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver la garantie
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }
}
