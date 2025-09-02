<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sinistre;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SinistreController extends Controller
{
    /**
     * Récupérer tous les sinistres de l'utilisateur
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Sinistre::with(['contract'])
                ->where('user_id', Auth::id());

            // Filtres optionnels
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('location', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
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
                'message' => 'Erreur lors de la récupération des sinistres',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer un sinistre spécifique
     */
    public function show(Sinistre $sinistre): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est propriétaire du sinistre
            if (Auth::id() !== $sinistre->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $sinistre->load(['contract']);

            return response()->json([
                'success' => true,
                'data' => $sinistre,
                'message' => 'Sinistre récupéré avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du sinistre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau sinistre
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'contract_id' => 'required|exists:contracts,id',
                'type' => 'required|in:collision,vol,incendie,bris,autre',
                'incident_date' => 'required|date',
                'location' => 'required|string|max:255',
                'description' => 'required|string',
                'estimated_damage' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérifier que le contrat appartient à l'utilisateur
            $contract = Contract::where('id', $request->contract_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contrat non trouvé ou non autorisé'
                ], 404);
            }

            $sinistre = Sinistre::create([
                'user_id' => Auth::id(),
                'contract_id' => $request->contract_id,
                'type' => $request->type,
                'incident_date' => $request->incident_date,
                'location' => $request->location,
                'description' => $request->description,
                'estimated_damage' => $request->estimated_damage,
                'status' => 'nouveau',
            ]);

            $sinistre->load(['contract']);

            return response()->json([
                'success' => true,
                'data' => $sinistre,
                'message' => 'Sinistre déclaré avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déclaration du sinistre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un sinistre
     */
    public function update(Request $request, Sinistre $sinistre): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est propriétaire du sinistre
            if (Auth::id() !== $sinistre->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Seuls les sinistres nouveaux peuvent être modifiés
            if ($sinistre->status !== 'nouveau') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce sinistre ne peut plus être modifié'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'estimated_damage' => 'sometimes|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sinistre->update($request->only([
                'location',
                'description',
                'estimated_damage'
            ]));

            $sinistre->load(['contract']);

            return response()->json([
                'success' => true,
                'data' => $sinistre,
                'message' => 'Sinistre mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du sinistre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un sinistre
     */
    public function destroy(Sinistre $sinistre): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est propriétaire du sinistre
            if (Auth::id() !== $sinistre->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Seuls les sinistres nouveaux peuvent être supprimés
            if ($sinistre->status !== 'nouveau') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce sinistre ne peut plus être supprimé'
                ], 400);
            }

            $sinistre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sinistre supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du sinistre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des sinistres
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Sinistre::where('user_id', Auth::id())->count(),
                'nouveau' => Sinistre::where('user_id', Auth::id())->nouveau()->count(),
                'en_cours' => Sinistre::where('user_id', Auth::id())->enCours()->count(),
                'valide' => Sinistre::where('user_id', Auth::id())->valide()->count(),
                'rejete' => Sinistre::where('user_id', Auth::id())->rejete()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
