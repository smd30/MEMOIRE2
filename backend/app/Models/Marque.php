<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'pays_origine',
        'logo_url',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les modèles
     */
    public function modeles()
    {
        return $this->hasMany(Modele::class);
    }

    /**
     * Obtenir les marques actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Rechercher par nom
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nom', 'like', "%{$search}%");
    }
}
