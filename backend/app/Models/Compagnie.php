<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compagnie extends Model
{
    use HasFactory;

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
        'commission_rate' => 'decimal:2'
    ];

    // Relations selon le diagramme UML
    public function garanties()
    {
        return $this->hasMany(Garantie::class);
    }

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    // Scopes utiles
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Méthodes utilitaires
    public function getNomFormateAttribute()
    {
        return ucfirst($this->nom);
    }

    public function getEmailFormateAttribute()
    {
        return strtolower($this->email);
    }

    public function getTelephoneFormateAttribute()
    {
        return $this->telephone ? '+221 ' . $this->telephone : null;
    }

    public function getCommissionRateFormateAttribute()
    {
        return number_format($this->commission_rate, 2) . '%';
    }

    public function getLogoUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('storage/' . $value);
        }
        return $value;
    }

    // Validation des données
    public static function getReglesValidation()
    {
        return [
            'nom' => 'required|string|max:100|unique:compagnies,nom',
            'description' => 'required|string|max:500',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:compagnies,email',
            'site_web' => 'nullable|url|max:255',
            'logo_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'api_endpoint' => 'nullable|url|max:255',
            'api_key' => 'nullable|string|max:255'
        ];
    }

    // Messages d'erreur personnalisés
    public static function getMessagesValidation()
    {
        return [
            'nom.unique' => 'Cette compagnie existe déjà.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'site_web.url' => 'Veuillez fournir une URL valide pour le site web.',
            'api_endpoint.url' => 'Veuillez fournir une URL valide pour l\'API.',
            'commission_rate.min' => 'Le taux de commission doit être positif.',
            'commission_rate.max' => 'Le taux de commission ne peut pas dépasser 100%.'
        ];
    }

    // Boot method pour initialiser les valeurs par défaut
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($compagnie) {
            if (!isset($compagnie->is_active)) {
                $compagnie->is_active = true;
            }
            if (!isset($compagnie->commission_rate)) {
                $compagnie->commission_rate = 15.00;
            }
        });
    }
}
