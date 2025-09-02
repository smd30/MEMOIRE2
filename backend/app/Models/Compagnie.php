<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compagnie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'compagnies';

    protected $fillable = [
        'nom',
        'description',
        'adresse',
        'telephone',
        'email',
        'site_web',
        'logo_url',
        'is_active',
        'commission_rate',
        'api_endpoint',
        'api_key'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relation avec les devis
     */
    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    /**
     * Relation avec les contrats
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Relation avec les garanties offertes
     */
    public function garanties()
    {
        return $this->belongsToMany(Garantie::class, 'compagnie_garantie')
                    ->withPivot('tarif', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Scope pour les compagnies actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtenir le logo de la compagnie
     */
    public function getLogoUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Logo par défaut
        return '/images/compagnies/default-logo.png';
    }
}
