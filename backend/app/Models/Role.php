<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les utilisateurs (many-to-many)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withTimestamps();
    }

    /**
     * Scope pour les rôles actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Vérifier si le rôle est actif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activer le rôle
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver le rôle
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Vérifier si c'est le rôle client
     */
    public function isClient(): bool
    {
        return $this->name === 'client';
    }

    /**
     * Vérifier si c'est le rôle gestionnaire
     */
    public function isGestionnaire(): bool
    {
        return $this->name === 'gestionnaire';
    }

    /**
     * Vérifier si c'est le rôle admin
     */
    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    /**
     * Obtenir le niveau de privilège du rôle
     */
    public function getPrivilegeLevel(): int
    {
        $levels = [
            'client' => 1,
            'gestionnaire' => 2,
            'admin' => 3,
        ];

        return $levels[$this->name] ?? 0;
    }

    /**
     * Vérifier si ce rôle a des privilèges supérieurs à un autre
     */
    public function hasHigherPrivilegesThan(Role $otherRole): bool
    {
        return $this->getPrivilegeLevel() > $otherRole->getPrivilegeLevel();
    }

    /**
     * Vérifier si ce rôle peut gérer un autre rôle
     */
    public function canManageRole(Role $otherRole): bool
    {
        return $this->hasHigherPrivilegesThan($otherRole);
    }

    /**
     * Obtenir les permissions associées au rôle
     */
    public function getPermissions(): array
    {
        $permissions = [
            'client' => [
                'view_own_contracts',
                'view_own_devis',
                'view_own_sinistres',
                'create_sinistre',
                'view_own_payments',
                'view_own_notifications',
            ],
            'gestionnaire' => [
                'view_all_contracts',
                'view_all_devis',
                'view_all_sinistres',
                'validate_sinistre',
                'cancel_contracts',
                'view_all_payments',
                'send_notifications',
                'view_reports',
            ],
            'admin' => [
                'view_all_contracts',
                'view_all_devis',
                'view_all_sinistres',
                'validate_sinistre',
                'cancel_contracts',
                'view_all_payments',
                'send_notifications',
                'view_reports',
                'manage_users',
                'manage_roles',
                'manage_system_settings',
                'view_audit_logs',
            ],
        ];

        return $permissions[$this->name] ?? [];
    }

    /**
     * Vérifier si le rôle a une permission spécifique
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    /**
     * Vérifier si le rôle peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Vérifier si le rôle peut gérer les contrats
     */
    public function canManageContracts(): bool
    {
        return $this->isGestionnaire() || $this->isAdmin();
    }

    /**
     * Vérifier si le rôle peut gérer les sinistres
     */
    public function canManageSinistres(): bool
    {
        return $this->isGestionnaire() || $this->isAdmin();
    }

    /**
     * Vérifier si le rôle peut voir les rapports
     */
    public function canViewReports(): bool
    {
        return $this->isGestionnaire() || $this->isAdmin();
    }

    /**
     * Vérifier si le rôle peut gérer les paramètres système
     */
    public function canManageSystemSettings(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Vérifier si le rôle peut voir les logs d'audit
     */
    public function canViewAuditLogs(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Créer les rôles de base
     */
    public static function createDefaultRoles(): void
    {
        $roles = [
            [
                'name' => 'client',
                'display_name' => 'Client',
                'description' => 'Utilisateur client avec accès limité à ses propres données',
                'is_active' => true,
            ],
            [
                'name' => 'gestionnaire',
                'display_name' => 'Gestionnaire',
                'description' => 'Gestionnaire avec accès aux contrats et sinistres',
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrateur',
                'description' => 'Administrateur avec accès complet au système',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            static::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
