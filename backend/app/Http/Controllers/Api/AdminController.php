<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Contract;
use App\Models\Sinistre;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Obtenir les statistiques du tableau de bord admin
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('statut', 'actif')->count(),
                'total_contracts' => Contract::count(),
                'total_sinistres' => Sinistre::count(),
                'total_revenue' => Contract::where('status', 'actif')->sum('total_premium'),
                'monthly_revenue' => Contract::where('status', 'actif')
                    ->whereMonth('created_at', now()->month)
                    ->sum('total_premium'),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'system_health' => 'good'
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques admin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
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
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('is_active')) {
                $query->where('statut', $request->is_active);
            }

            $users = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Utilisateurs récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des utilisateurs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs'
            ], 500);
        }
    }

    /**
     * Obtenir un utilisateur par ID
     */
    public function getUserById(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'utilisateur'
            ], 500);
        }
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'MotDePasse' => 'required|string|min:6',
                'Telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'role' => 'required|in:client,gestionnaire,admin',
                'statut' => 'nullable|in:actif,inactif'
            ]);

            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur'
            ], 500);
        }
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(int $id, Request $request): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'Telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'role' => 'sometimes|in:client,gestionnaire,admin',
                'statut' => 'sometimes|in:actif,inactif'
            ]);

            $user->update($validated);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour d\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur'
            ], 500);
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression d\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur'
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un utilisateur
     */
    public function toggleUserStatus(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->statut = $user->statut === 'actif' ? 'inactif' : 'actif';
            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Statut utilisateur mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }

    /**
     * Obtenir les logs système
     */
    public function getSystemLogs(Request $request): JsonResponse
    {
        try {
            $logs = [
                [
                    'id' => 1,
                    'level' => 'info',
                    'message' => 'Système démarré avec succès',
                    'context' => ['user_id' => 1],
                    'created_at' => now()->toISOString()
                ],
                [
                    'id' => 2,
                    'level' => 'info',
                    'message' => 'Nouvelle connexion utilisateur',
                    'context' => ['user_id' => 2],
                    'created_at' => now()->subMinutes(5)->toISOString()
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
                    return str_contains(strtolower($log['message']), strtolower($search));
                });
            }

            return response()->json([
                'success' => true,
                'data' => array_values($logs),
                'message' => 'Logs système récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des logs'
            ], 500);
        }
    }

    /**
     * Effacer les logs système
     */
    public function clearSystemLogs(): JsonResponse
    {
        try {
            // En production, on effacerait les vrais logs
            // Pour l'instant, on simule juste
            return response()->json([
                'success' => true,
                'message' => 'Logs système effacés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'effacement des logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'effacement des logs'
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
                'cpu_usage' => rand(20, 80),
                'memory_usage' => rand(30, 90),
                'disk_usage' => rand(40, 85),
                'active_connections' => rand(50, 200),
                'uptime' => '15 jours, 8 heures',
                'last_backup' => now()->subDay()->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques système récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des stats système: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques système'
            ], 500);
        }
    }

    /**
     * Obtenir les sauvegardes
     */
    public function getBackups(): JsonResponse
    {
        try {
            $backups = [
                [
                    'id' => 'backup_20240115_020000',
                    'filename' => 'backup_20240115_020000.sql',
                    'size' => '45.2 MB',
                    'created_at' => '2024-01-15 02:00:00',
                    'status' => 'completed'
                ],
                [
                    'id' => 'backup_20240114_020000',
                    'filename' => 'backup_20240114_020000.sql',
                    'size' => '44.8 MB',
                    'created_at' => '2024-01-14 02:00:00',
                    'status' => 'completed'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $backups,
                'message' => 'Sauvegardes récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des sauvegardes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sauvegardes'
            ], 500);
        }
    }

    /**
     * Créer une sauvegarde
     */
    public function createBackup(): JsonResponse
    {
        try {
            // En production, on créerait une vraie sauvegarde
            $backupId = 'backup_' . now()->format('Ymd_His');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $backupId,
                    'filename' => $backupId . '.sql',
                    'size' => '45.5 MB',
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'status' => 'completed'
                ],
                'message' => 'Sauvegarde créée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de sauvegarde: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la sauvegarde'
            ], 500);
        }
    }

    /**
     * Restaurer une sauvegarde
     */
    public function restoreBackup(string $backupId): JsonResponse
    {
        try {
            // En production, on restaurerait la vraie sauvegarde
            return response()->json([
                'success' => true,
                'message' => 'Sauvegarde restaurée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de sauvegarde: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la restauration de la sauvegarde'
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
                'app_name' => 'Assurance Auto Platform',
                'app_version' => '1.0.0',
                'max_file_size' => '10MB',
                'session_timeout' => 3600,
                'email_notifications' => true,
                'maintenance_mode' => false,
                'backup_frequency' => 'daily'
            ];

            return response()->json([
                'success' => true,
                'data' => $config,
                'message' => 'Configuration système récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la config: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la configuration'
            ], 500);
        }
    }

    /**
     * Mettre à jour la configuration système
     */
    public function updateSystemConfig(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'app_name' => 'sometimes|string',
                'app_version' => 'sometimes|string',
                'max_file_size' => 'sometimes|string',
                'session_timeout' => 'sometimes|integer',
                'email_notifications' => 'sometimes|boolean',
                'maintenance_mode' => 'sometimes|boolean',
                'backup_frequency' => 'sometimes|string'
            ]);

            // En production, on sauvegarderait dans la base de données
            return response()->json([
                'success' => true,
                'data' => $validated,
                'message' => 'Configuration mise à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la config: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la configuration'
            ], 500);
        }
    }

    /**
     * Basculer le mode maintenance
     */
    public function toggleMaintenanceMode(): JsonResponse
    {
        try {
            // En production, on basculerait le vrai mode maintenance
            return response()->json([
                'success' => true,
                'message' => 'Mode maintenance basculé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du basculement du mode maintenance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du basculement du mode maintenance'
            ], 500);
        }
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): JsonResponse
    {
        try {
            // En production, on effacerait le vrai cache
            return response()->json([
                'success' => true,
                'message' => 'Cache effacé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'effacement du cache: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'effacement du cache'
            ], 500);
        }
    }
}
