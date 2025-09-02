<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Sinistre;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GestionnaireController extends Controller
{
    /**
     * Obtenir les statistiques du tableau de bord
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_contracts' => Contract::count(),
                'active_contracts' => Contract::where('status', 'actif')->count(),
                'expired_contracts' => Contract::where('status', 'expire')->count(),
                'total_sinistres' => Sinistre::count(),
                'pending_sinistres' => Sinistre::where('status', 'nouveau')->count(),
                'total_users' => User::count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'total_revenue' => Contract::where('status', 'actif')->sum('total_premium'),
                'monthly_revenue' => Contract::where('status', 'actif')
                    ->whereMonth('created_at', now()->month)
                    ->sum('total_premium')
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
     * Obtenir tous les contrats avec filtres
     */
    public function getAllContracts(Request $request): JsonResponse
    {
        try {
            $query = Contract::with(['user', 'vehicle']);

            // Filtres
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('contract_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('nom', 'like', "%{$search}%")
                                   ->orWhere('prenom', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                          $vehicleQuery->where('marqueVehicule', 'like', "%{$search}%")
                                      ->orWhere('modèle', 'like', "%{$search}%")
                                      ->orWhere('immatriculation', 'like', "%{$search}%");
                      });
                });
            }

            $contracts = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $contracts,
                'message' => 'Contrats récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des contrats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir un contrat spécifique
     */
    public function getContractById(Contract $contract): JsonResponse
    {
        try {
            $contract->load(['user', 'vehicle']);

            return response()->json([
                'success' => true,
                'data' => $contract,
                'message' => 'Contrat récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du contrat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annuler un contrat
     */
    public function cancelContract(Request $request, Contract $contract): JsonResponse
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $contract->update([
                'status' => 'annule',
                'notes' => $request->reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contrat annulé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation du contrat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir tous les sinistres avec filtres
     */
    public function getAllSinistres(Request $request): JsonResponse
    {
        try {
            $query = Sinistre::with(['user', 'contract.vehicle']);

            // Filtres
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('sinistre_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('nom', 'like', "%{$search}%")
                                   ->orWhere('prenom', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $sinistres = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $sinistres,
                'message' => 'Sinistres récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sinistres: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir un sinistre spécifique
     */
    public function getSinistreById(Sinistre $sinistre): JsonResponse
    {
        try {
            $sinistre->load(['user', 'contract.vehicle']);

            return response()->json([
                'success' => true,
                'data' => $sinistre,
                'message' => 'Sinistre récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du sinistre: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un sinistre
     */
    public function updateSinistre(Request $request, Sinistre $sinistre): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:nouveau,en_cours,valide,rejete',
                'manager_notes' => 'nullable|string|max:1000'
            ]);

            $sinistre->update([
                'status' => $request->status,
                'manager_notes' => $request->manager_notes,
                'managed_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'data' => $sinistre,
                'message' => 'Sinistre mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du sinistre: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assigner un expert à un sinistre
     */
    public function assignExpert(Request $request, Sinistre $sinistre): JsonResponse
    {
        try {
            $request->validate([
                'expert_id' => 'required|exists:users,id'
            ]);

            $sinistre->update([
                'managed_by' => $request->expert_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expert assigné avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'assignation de l\'expert: ' . $e->getMessage()
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
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('Telephone', 'like', "%{$search}%");
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
     * Envoyer une notification
     */
    public function sendNotification(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'title' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
                'type' => 'required|in:info,warning,error,success'
            ]);

            // Ici vous pouvez implémenter l'envoi de notifications
            // Par exemple, enregistrer dans une table de notifications
            // ou envoyer des emails

            return response()->json([
                'success' => true,
                'message' => 'Notification envoyée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer un rapport
     */
    public function generateReport(Request $request, string $type): JsonResponse
    {
        try {
            $filters = $request->all();
            $report = [];

            switch ($type) {
                case 'contracts':
                    $report = $this->generateContractsReport($filters);
                    break;
                case 'sinistres':
                    $report = $this->generateSinistresReport($filters);
                    break;
                case 'users':
                    $report = $this->generateUsersReport($filters);
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Type de rapport non supporté'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Rapport généré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer un rapport des contrats
     */
    private function generateContractsReport(array $filters): array
    {
        $query = Contract::with(['user', 'vehicle']);

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        $contracts = $query->get();

        return [
            'total' => $contracts->count(),
            'actif' => $contracts->where('status', 'actif')->count(),
            'expire' => $contracts->where('status', 'expire')->count(),
            'annule' => $contracts->where('status', 'annule')->count(),
            'total_revenue' => $contracts->sum('total_premium'),
            'contracts' => $contracts
        ];
    }

    /**
     * Générer un rapport des sinistres
     */
    private function generateSinistresReport(array $filters): array
    {
        $query = Sinistre::with(['user', 'contract.vehicle']);

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        $sinistres = $query->get();

        return [
            'total' => $sinistres->count(),
            'nouveau' => $sinistres->where('status', 'nouveau')->count(),
            'en_cours' => $sinistres->where('status', 'en_cours')->count(),
            'valide' => $sinistres->where('status', 'valide')->count(),
            'rejete' => $sinistres->where('status', 'rejete')->count(),
            'total_damage' => $sinistres->sum('estimated_damage'),
            'sinistres' => $sinistres
        ];
    }

    /**
     * Générer un rapport des utilisateurs
     */
    private function generateUsersReport(array $filters): array
    {
        $query = User::query();

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        $users = $query->get();

        return [
            'total' => $users->count(),
            'actif' => $users->where('statut', 'actif')->count(),
            'inactif' => $users->where('statut', 'inactif')->count(),
            'new_this_month' => $users->where('created_at', '>=', now()->startOfMonth())->count(),
            'users' => $users
        ];
    }
}
