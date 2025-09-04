<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque_id',
        'nom',
        'annee_debut',
        'annee_fin',
        'categorie_vehicule',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec la marque
     */
    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    /**
     * Obtenir les modèles actifs
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

    /**
     * Filtrer par marque
     */
    public function scopeByMarque($query, $marqueId)
    {
        return $query->where('marque_id', $marqueId);
    }

    /**
     * Obtenir le nom complet (Marque + Modèle)
     */
    public function getNomCompletAttribute()
    {
        return $this->marque ? $this->marque->nom . ' ' . $this->nom : $this->nom;
    }
}
