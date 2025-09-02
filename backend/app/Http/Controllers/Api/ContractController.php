<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Vehicle;
use App\Models\Compagnie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    /**
     * Récupérer tous les contrats de l'utilisateur
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Contract::with(['vehicle', 'garanties'])
                ->where('user_id', Auth::id());

            // Filtres optionnels
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('contract_number', 'like', "%{$search}%")
                      ->orWhereHas('vehicle', function($vq) use ($search) {
                          $vq->where('brand', 'like', "%{$search}%")
                             ->orWhere('model', 'like', "%{$search}%")
                             ->orWhere('plate_number', 'like', "%{$search}%");
                      });
                });
            }

            $contrats = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $contrats,
                'message' => 'Contrats récupérés avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des contrats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer un contrat spécifique
     */
    public function show(Contract $contract): JsonResponse
    {
        try {
            // Vérifier l'autorisation
            if (Auth::id() !== $contract->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $contract->load(['vehicle', 'garanties']);

            return response()->json([
                'success' => true,
                'data' => $contract,
                'message' => 'Contrat récupéré avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du contrat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau contrat
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'compagnie_id' => 'required|exists:compagnies,id',
            'garanties' => 'required|array|min:1',
            'garanties.*' => 'exists:garanties,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'montant_annuel' => 'required|numeric|min:0',
            'montant_mensuel' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérifier que le véhicule appartient à l'utilisateur
            $vehicle = Vehicle::where('id', $request->vehicle_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Véhicule non trouvé ou non autorisé'
                ], 404);
            }

            // Générer le numéro de contrat
            $numeroContrat = 'CTR-' . date('Y') . '-' . str_pad(Contract::count() + 1, 3, '0', STR_PAD_LEFT);

            $contract = Contract::create([
                'numero_contrat' => $numeroContrat,
                'user_id' => Auth::id(),
                'vehicle_id' => $request->vehicle_id,
                'compagnie_id' => $request->compagnie_id,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'montant_annuel' => $request->montant_annuel,
                'montant_mensuel' => $request->montant_mensuel,
                'statut' => 'actif',
            ]);

            // Attacher les garanties
            $contract->garanties()->attach($request->garanties);

            $contract->load(['vehicle', 'compagnie', 'garanties']);

            return response()->json([
                'success' => true,
                'data' => $contract,
                'message' => 'Contrat créé avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du contrat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Renouveler un contrat
     */
    public function renew(Contract $contract): JsonResponse
    {
        try {
            // Vérifier l'autorisation
            if (Auth::id() !== $contract->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Vérifier que le contrat est actif
            if ($contract->statut !== 'actif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les contrats actifs peuvent être renouvelés'
                ], 400);
            }

            // Créer un nouveau contrat avec les mêmes conditions
            $newContract = $contract->replicate();
            $newContract->numero_contrat = 'CTR-' . date('Y') . '-' . str_pad(Contract::count() + 1, 3, '0', STR_PAD_LEFT);
            $newContract->date_debut = $contract->date_fin;
            $newContract->date_fin = date('Y-m-d', strtotime($contract->date_fin . ' +1 year'));
            $newContract->created_at = now();
            $newContract->updated_at = now();
            $newContract->save();

            // Copier les garanties
            $garanties = $contract->garanties()->pluck('garanties.id')->toArray();
            $newContract->garanties()->attach($garanties);

            // Marquer l'ancien contrat comme expiré
            $contract->update(['statut' => 'expire']);

            $newContract->load(['vehicle', 'compagnie', 'garanties']);

            return response()->json([
                'success' => true,
                'data' => $newContract,
                'message' => 'Contrat renouvelé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du renouvellement du contrat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger l'attestation
     */
    public function downloadAttestation(Contract $contract): JsonResponse
    {
        try {
            // Vérifier l'autorisation
            if (Auth::id() !== $contract->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Vérifier que le contrat est actif
            if ($contract->statut !== 'actif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les contrats actifs peuvent générer une attestation'
                ], 400);
            }

            // Ici, vous pouvez générer un PDF d'attestation
            // Pour l'instant, on retourne un message de succès
            return response()->json([
                'success' => true,
                'message' => 'Attestation générée avec succès',
                'data' => [
                    'numero_contrat' => $contract->numero_contrat,
                    'date_generation' => now(),
                    'url' => '/attestations/' . $contract->numero_contrat . '.pdf'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération de l\'attestation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer/Résilier un contrat
     */
    public function destroy(Contract $contract): JsonResponse
    {
        try {
            // Vérifier l'autorisation
            if (Auth::id() !== $contract->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Marquer le contrat comme résilié
            $contract->update(['statut' => 'resilie']);

            return response()->json([
                'success' => true,
                'message' => 'Contrat résilié avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la résiliation du contrat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir l'échéancier des contrats
     */
    public function getEcheancier(): JsonResponse
    {
        try {
            $echeancier = Contract::with(['vehicle', 'compagnie'])
                ->where('user_id', Auth::id())
                ->where('statut', 'actif')
                ->where('date_fin', '>=', now())
                ->orderBy('date_fin', 'asc')
                ->get()
                ->map(function($contract) {
                    return [
                        'id' => $contract->id,
                        'numero_contrat' => $contract->numero_contrat,
                        'date_fin' => $contract->date_fin,
                        'jours_restants' => now()->diffInDays($contract->date_fin, false),
                        'vehicle' => $contract->vehicle,
                        'compagnie' => $contract->compagnie,
                        'montant_mensuel' => $contract->montant_mensuel
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $echeancier,
                'message' => 'Échéancier récupéré avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'échéancier',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
