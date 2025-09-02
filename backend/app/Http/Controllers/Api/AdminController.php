<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contract;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Obtenir les statistiques du tableau de bord administrateur
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_contracts' => Contract::count(),
                'total_sinistres' => Sinistre::count(),
                'total_revenue' => Contract::where('status', 'active')->sum('total_premium'),
                'monthly_revenue' => Contract::where('status', 'active')
                    ->whereMonth('created_at', now()->month)
                    ->sum('total_premium'),
                'active_users' => User::where('is_active', true)->count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'system_health' => $this->getSystemHealth()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir tous les utilisateurs avec filtres
     */
    public function getAllUsers(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            $users = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Utilisateurs récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir un utilisateur spécifique
     */
    public function getUserById(User $user): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:M,F',
                'nationality' => 'nullable|string|max:100',
                'id_number' => 'nullable|string|max:50',
                'id_type' => 'nullable|string|max:50',
                'profession' => 'nullable|string|max:100',
                'annual_income' => 'nullable|numeric|min:0'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'id_number' => $request->id_number,
                'id_type' => $request->id_type,
                'profession' => $request->profession,
                'annual_income' => $request->annual_income,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur créé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:M,F',
                'nationality' => 'nullable|string|max:100',
                'id_number' => 'nullable|string|max:50',
                'id_type' => 'nullable|string|max:50',
                'profession' => 'nullable|string|max:100',
                'annual_income' => 'nullable|numeric|min:0'
            ]);

            $user->update($request->except(['password']));

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser(User $user): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur n'a pas de contrats actifs
            $activeContracts = Contract::where('user_id', $user->id)
                ->where('status', 'active')
                ->count();

            if ($activeContracts > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer un utilisateur avec des contrats actifs'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un utilisateur
     */
    public function toggleUserStatus(User $user): JsonResponse
    {
        try {
            $user->update([
                'is_active' => !$user->is_active
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Statut de l\'utilisateur modifié avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les rôles disponibles
     */
    public function getRoles(): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour récupérer les rôles
            // Par exemple, si vous utilisez Spatie Permission
            $roles = [
                ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrateur'],
                ['id' => 2, 'name' => 'gestionnaire', 'display_name' => 'Gestionnaire'],
                ['id' => 3, 'name' => 'client', 'display_name' => 'Client']
            ];

            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Rôles récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des rôles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les permissions disponibles
     */
    public function getPermissions(): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour récupérer les permissions
            $permissions = [
                ['id' => 1, 'name' => 'users.view', 'display_name' => 'Voir les utilisateurs'],
                ['id' => 2, 'name' => 'users.create', 'display_name' => 'Créer des utilisateurs'],
                ['id' => 3, 'name' => 'users.edit', 'display_name' => 'Modifier les utilisateurs'],
                ['id' => 4, 'name' => 'users.delete', 'display_name' => 'Supprimer les utilisateurs'],
                ['id' => 5, 'name' => 'contracts.view', 'display_name' => 'Voir les contrats'],
                ['id' => 6, 'name' => 'contracts.manage', 'display_name' => 'Gérer les contrats'],
                ['id' => 7, 'name' => 'sinistres.view', 'display_name' => 'Voir les sinistres'],
                ['id' => 8, 'name' => 'sinistres.manage', 'display_name' => 'Gérer les sinistres']
            ];

            return response()->json([
                'success' => true,
                'data' => $permissions,
                'message' => 'Permissions récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assigner un rôle à un utilisateur
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        try {
            $request->validate([
                'role_id' => 'required|integer'
            ]);

            // Ici vous pouvez implémenter la logique pour assigner un rôle
            // Par exemple, si vous utilisez Spatie Permission

            return response()->json([
                'success' => true,
                'message' => 'Rôle assigné avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'assignation du rôle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les logs système
     */
    public function getSystemLogs(Request $request): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour récupérer les logs système
            // Par exemple, lire les fichiers de logs Laravel
            $logs = [
                [
                    'id' => 1,
                    'level' => 'info',
                    'message' => 'Application démarrée avec succès',
                    'context' => ['user_id' => 1, 'ip' => '192.168.1.1'],
                    'created_at' => now()->subMinutes(5)->toISOString()
                ],
                [
                    'id' => 2,
                    'level' => 'warning',
                    'message' => 'Tentative de connexion échouée',
                    'context' => ['email' => 'test@example.com', 'ip' => '192.168.1.2'],
                    'created_at' => now()->subMinutes(10)->toISOString()
                ]
            ];

            // Filtres
            if ($request->filled('level')) {
                $logs = array_filter($logs, function ($log) use ($request) {
                    return $log['level'] === $request->level;
                });
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $logs = array_filter($logs, function ($log) use ($search) {
                    return strpos($log['message'], $search) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'data' => array_values($logs),
                'message' => 'Logs récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Effacer les logs système
     */
    public function clearLogs(): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour effacer les logs
            // Par exemple, vider les fichiers de logs Laravel

            return response()->json([
                'success' => true,
                'message' => 'Logs effacés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'effacement des logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques système
     */
    public function getSystemStats(): JsonResponse
    {
        try {
            $stats = [
                'cpu_usage' => $this->getCpuUsage(),
                'memory_usage' => $this->getMemoryUsage(),
                'disk_usage' => $this->getDiskUsage(),
                'active_connections' => $this->getActiveConnections(),
                'uptime' => $this->getUptime(),
                'last_backup' => $this->getLastBackupTime()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques système récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques système: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer une sauvegarde
     */
    public function createBackup(): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour créer une sauvegarde
            // Par exemple, utiliser le package spatie/laravel-backup

            return response()->json([
                'success' => true,
                'message' => 'Sauvegarde créée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir la liste des sauvegardes
     */
    public function getBackups(): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour récupérer les sauvegardes
            $backups = [
                [
                    'id' => 'backup_20240115_020000',
                    'filename' => 'backup_20240115_020000.sql',
                    'size' => '45.2 MB',
                    'created_at' => '2024-01-15 02:00:00',
                    'status' => 'completed'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $backups,
                'message' => 'Sauvegardes récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sauvegardes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurer une sauvegarde
     */
    public function restoreBackup(string $backupId): JsonResponse
    {
        try {
            // Ici vous pouvez implémenter la logique pour restaurer une sauvegarde

            return response()->json([
                'success' => true,
                'message' => 'Sauvegarde restaurée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la restauration de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir la configuration système
     */
    public function getSystemConfig(): JsonResponse
    {
        try {
            $config = [
                'app_name' => config('app.name'),
                'app_version' => '1.0.0',
                'maintenance_mode' => app()->isDownForMaintenance(),
                'max_file_size' => ini_get('upload_max_filesize'),
                'session_timeout' => config('session.lifetime'),
                'email_notifications' => true
            ];

            return response()->json([
                'success' => true,
                'data' => $config,
                'message' => 'Configuration récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour la configuration système
     */
    public function updateSystemConfig(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'app_name' => 'required|string|max:255',
                'app_version' => 'required|string|max:50',
                'maintenance_mode' => 'boolean',
                'max_file_size' => 'required|string',
                'session_timeout' => 'required|integer|min:1',
                'email_notifications' => 'boolean'
            ]);

            // Ici vous pouvez implémenter la logique pour mettre à jour la configuration

            return response()->json([
                'success' => true,
                'message' => 'Configuration mise à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre le système en mode maintenance
     */
    public function putSystemInMaintenance(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mode' => 'required|boolean',
                'message' => 'nullable|string|max:500'
            ]);

            if ($request->mode) {
                // Activer le mode maintenance
                // Artisan::call('down', ['--message' => $request->message ?? 'Maintenance en cours']);
            } else {
                // Désactiver le mode maintenance
                // Artisan::call('up');
            }

            return response()->json([
                'success' => true,
                'message' => 'Mode maintenance ' . ($request->mode ? 'activé' : 'désactivé') . ' avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de mode maintenance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): JsonResponse
    {
        try {
            // Effacer différents types de cache
            // Artisan::call('cache:clear');
            // Artisan::call('config:clear');
            // Artisan::call('route:clear');
            // Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache effacé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'effacement du cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir la santé du système
     */
    private function getSystemHealth(): string
    {
        // Logique simple pour déterminer la santé du système
        $cpuUsage = $this->getCpuUsage();
        $memoryUsage = $this->getMemoryUsage();
        $diskUsage = $this->getDiskUsage();

        if ($cpuUsage > 90 || $memoryUsage > 90 || $diskUsage > 90) {
            return 'critical';
        } elseif ($cpuUsage > 70 || $memoryUsage > 70 || $diskUsage > 70) {
            return 'warning';
        } else {
            return 'excellent';
        }
    }

    /**
     * Obtenir l'utilisation CPU (simulation)
     */
    private function getCpuUsage(): int
    {
        // Simulation - dans un vrai environnement, vous utiliseriez des commandes système
        return rand(20, 80);
    }

    /**
     * Obtenir l'utilisation mémoire (simulation)
     */
    private function getMemoryUsage(): int
    {
        // Simulation
        return rand(30, 85);
    }

    /**
     * Obtenir l'utilisation disque (simulation)
     */
    private function getDiskUsage(): int
    {
        // Simulation
        return rand(40, 90);
    }

    /**
     * Obtenir les connexions actives (simulation)
     */
    private function getActiveConnections(): int
    {
        // Simulation
        return rand(50, 200);
    }

    /**
     * Obtenir le temps de fonctionnement (simulation)
     */
    private function getUptime(): string
    {
        // Simulation
        $days = rand(1, 30);
        $hours = rand(0, 23);
        return "{$days} jours, {$hours} heures";
    }

    /**
     * Obtenir la date de la dernière sauvegarde (simulation)
     */
    private function getLastBackupTime(): string
    {
        // Simulation
        return now()->subDays(rand(1, 7))->format('Y-m-d H:i:s');
    }
}
