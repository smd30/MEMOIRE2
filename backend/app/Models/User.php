<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'MotDePasse',
        'Telephone',
        'adresse',
        'role',
        'statut',
    ];

    protected $hidden = [
        'MotDePasse',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    /**
     * Relation avec le profil client
     */
    public function clientProfile(): HasOne
    {
        return $this->hasOne(ClientProfile::class);
    }

    /**
     * Relation avec les véhicules
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Relation avec les contrats
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Relation avec les devis
     */
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    /**
     * Relation avec les sinistres
     */
    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class);
    }

    /**
     * Relation avec les notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relation avec les sinistres gérés (pour les gestionnaires)
     */
    public function managedSinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'managed_by');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur est un client
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Vérifier si l'utilisateur est un gestionnaire
     */
    public function isGestionnaire(): bool
    {
        return $this->hasRole('gestionnaire');
    }

    /**
     * Vérifier si l'utilisateur est un admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Vérifier si l'utilisateur peut gérer les contrats
     */
    public function canManageContracts(): bool
    {
        return $this->isGestionnaire() || $this->isAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut gérer les sinistres
     */
    public function canManageSinistres(): bool
    {
        return $this->isGestionnaire() || $this->isAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Activer l'utilisateur
     */
    public function activate(): void
    {
        $this->update(['statut' => 'actif']);
    }

    /**
     * Désactiver l'utilisateur
     */
    public function deactivate(): void
    {
        $this->update(['statut' => 'inactif']);
    }
}
