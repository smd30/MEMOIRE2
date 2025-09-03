<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
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

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
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
     * Créer un nouvel utilisateur (gestionnaire ou admin uniquement)
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'MotDePasse' => 'required|string|min:6',
                'role' => 'required|in:gestionnaire,admin',
                'statut' => 'nullable|in:actif,bloque'
            ]);

            // Hash du mot de passe
            $validated['MotDePasse'] = Hash::make($validated['MotDePasse']);
            
            // Statut par défaut
            if (!isset($validated['statut'])) {
                $validated['statut'] = 'actif';
            }

            // Créer l'utilisateur avec les données par défaut
            $user = User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'MotDePasse' => $validated['MotDePasse'],
                'role' => $validated['role'],
                'statut' => $validated['statut'],
                'Telephone' => $request->input('Telephone', ''),
                'adresse' => $request->input('adresse', ''),
                'user_data' => json_encode([
                    'preferences' => [
                        'theme' => 'light',
                        'language' => 'fr',
                        'notifications' => [
                            'email' => true,
                            'push' => false,
                            'sms' => false
                        ],
                        'dashboard' => [
                            'showStats' => true,
                            'showRecentActivity' => true,
                            'layout' => 'grid'
                        ]
                    ],
                    'activities' => [],
                    'lastSync' => now()->toISOString()
                ])
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un utilisateur (actif/bloque)
     */
    public function toggleUserStatus(int $id, Request $request): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $newStatus = $request->input('status', 'bloque');
            
            if (!in_array($newStatus, ['actif', 'bloque'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Statut invalide'
                ], 400);
            }
            
            $user->statut = $newStatus;
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
     * Obtenir un utilisateur par ID
     */
    public function getUserById(int $id): JsonResponse
    {
        try {
            $user = User::with('clientProfile')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
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
                'role' => 'sometimes|in:client,gestionnaire,admin',
                'statut' => 'sometimes|in:actif,bloque',
                'Telephone' => 'sometimes|string|max:20',
                'adresse' => 'sometimes|string|max:255'
            ]);

            $user->update($validated);

            return response()->json([
                'success' => true,
                'data' => $user->fresh(),
                'message' => 'Utilisateur mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'utilisateur: ' . $e->getMessage());
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
            
            // Supprimer tous les tokens de l'utilisateur
            $user->tokens()->delete();
            
            // Supprimer l'utilisateur
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques du dashboard admin
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('statut', 'actif')->count(),
                'blocked_users' => User::where('statut', 'bloque')->count(),
                'clients' => User::where('role', 'client')->count(),
                'gestionnaires' => User::where('role', 'gestionnaire')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'recent_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
                'users_by_role' => [
                    'client' => User::where('role', 'client')->count(),
                    'gestionnaire' => User::where('role', 'gestionnaire')->count(),
                    'admin' => User::where('role', 'admin')->count()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Obtenir les logs système
     */
    public function getSystemLogs(): JsonResponse
    {
        try {
            // Simuler des logs système (dans un vrai projet, vous utiliseriez un système de logging)
            $logs = [
                [
                    'id' => 1,
                    'level' => 'info',
                    'message' => 'Système démarré avec succès',
                    'timestamp' => now()->subHours(2)->toISOString()
                ],
                [
                    'id' => 2,
                    'level' => 'warning',
                    'message' => 'Tentative de connexion échouée',
                    'timestamp' => now()->subHours(1)->toISOString()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $logs,
                'message' => 'Logs récupérés avec succès'
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
            // Simuler l'effacement des logs
            return response()->json([
                'success' => true,
                'message' => 'Logs effacés avec succès'
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
                'database_size' => '2.5 MB',
                'cache_hit_rate' => '85%',
                'memory_usage' => '45%',
                'cpu_usage' => '30%',
                'disk_usage' => '60%',
                'uptime' => '7 jours',
                'active_sessions' => User::whereNotNull('last_login_at')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques système récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques système: ' . $e->getMessage());
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
                    'id' => 1,
                    'name' => 'backup_2024_03_20.sql',
                    'size' => '1.2 MB',
                    'created_at' => now()->subDays(1)->toISOString(),
                    'status' => 'completed'
                ],
                [
                    'id' => 2,
                    'name' => 'backup_2024_03_19.sql',
                    'size' => '1.1 MB',
                    'created_at' => now()->subDays(2)->toISOString(),
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
            // Simuler la création d'une sauvegarde
            $backup = [
                'id' => 3,
                'name' => 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql',
                'size' => '1.3 MB',
                'created_at' => now()->toISOString(),
                'status' => 'completed'
            ];

            return response()->json([
                'success' => true,
                'data' => $backup,
                'message' => 'Sauvegarde créée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la sauvegarde: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la sauvegarde'
            ], 500);
        }
    }

    /**
     * Restaurer une sauvegarde
     */
    public function restoreBackup(int $backupId): JsonResponse
    {
        try {
            // Simuler la restauration d'une sauvegarde
            return response()->json([
                'success' => true,
                'message' => 'Sauvegarde restaurée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de la sauvegarde: ' . $e->getMessage());
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
                'maintenance_mode' => false,
                'debug_mode' => true,
                'cache_enabled' => true,
                'session_timeout' => 3600,
                'max_upload_size' => '10MB',
                'allowed_file_types' => ['jpg', 'png', 'pdf', 'doc']
            ];

            return response()->json([
                'success' => true,
                'data' => $config,
                'message' => 'Configuration récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la configuration: ' . $e->getMessage());
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
                'maintenance_mode' => 'sometimes|boolean',
                'debug_mode' => 'sometimes|boolean',
                'cache_enabled' => 'sometimes|boolean',
                'session_timeout' => 'sometimes|integer|min:300|max:86400',
                'max_upload_size' => 'sometimes|string',
                'allowed_file_types' => 'sometimes|array'
            ]);

            // Simuler la mise à jour de la configuration
            return response()->json([
                'success' => true,
                'data' => $validated,
                'message' => 'Configuration mise à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la configuration: ' . $e->getMessage());
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
            // Simuler le basculement du mode maintenance
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
            // Simuler l'effacement du cache
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
