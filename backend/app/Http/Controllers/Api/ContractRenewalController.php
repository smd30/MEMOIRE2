<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContractRenewal;
use App\Models\Contrat;
use App\Models\Vehicule;
use App\Models\Compagnie;
use App\Services\ContractRenewalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContractRenewalController extends Controller
{
    protected $renewalService;

    public function __construct(ContractRenewalService $renewalService)
    {
        $this->renewalService = $renewalService;
    }
    /**
     * Afficher la liste des renouvellements
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $query = ContractRenewal::with(['contrat', 'vehicule', 'compagnie'])
                ->byUser($user->id);

            // Filtres
            if ($request->has('statut')) {
                $query->where('statut', $request->statut);
            }

            if ($request->has('compagnie_id')) {
                $query->where('compagnie_id', $request->compagnie_id);
            }

            if ($request->has('date_debut') && $request->has('date_fin')) {
                $query->whereBetween('date_demande', [
                    $request->date_debut,
                    $request->date_fin
                ]);
            }

            $renewals = $query->orderBy('date_demande', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Liste des renouvellements récupérée avec succès',
                'data' => $renewals
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des renouvellements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer une nouvelle demande de renouvellement
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), ContractRenewal::getReglesValidation(), ContractRenewal::getMessagesValidation());

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $contrat = Contrat::findOrFail($request->contrat_id);

            // Vérifier que l'utilisateur est propriétaire du contrat
            if ($contrat->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à renouveler ce contrat'
                ], 403);
            }

            // Vérifier que le contrat peut être renouvelé
            if (!$this->renewalService->peutEtreRenouvele($contrat)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce contrat ne peut pas être renouvelé'
                ], 400);
            }

            DB::beginTransaction();

            $renewalData = $request->all();
            $renewalData['user_id'] = $user->id;
            $renewalData['vehicule_id'] = $contrat->vehicule_id;
            $renewalData['compagnie_id'] = $contrat->compagnie_id;
            $renewalData['numero_police_precedent'] = $contrat->numero_police;
            $renewalData['date_demande'] = now();

            // Calculer les dates du nouveau contrat
            $dates = $this->renewalService->calculerDatesRenouvellement($contrat, $request->periode_police);
            $renewalData['date_debut_nouveau'] = $dates['date_debut'];
            $renewalData['date_fin_nouveau'] = $dates['date_fin'];

            $renewal = ContractRenewal::create($renewalData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Demande de renouvellement créée avec succès',
                'data' => $renewal->load(['contrat', 'vehicule', 'compagnie'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la demande de renouvellement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un renouvellement spécifique
     */
    public function show($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $renewal = ContractRenewal::with(['contrat', 'vehicule', 'compagnie'])
                ->byUser($user->id)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Renouvellement récupéré avec succès',
                'data' => $renewal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Renouvellement non trouvé',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mettre à jour un renouvellement
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $renewal = ContractRenewal::byUser($user->id)->findOrFail($id);

            // Vérifier que le renouvellement peut être modifié
            if (!$this->renewalService->peutEtreModifie($renewal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce renouvellement ne peut pas être modifié'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'periode_police' => 'sometimes|integer|in:1,3,6,12',
                'garanties_selectionnees' => 'sometimes|array',
                'prime_rc' => 'sometimes|numeric|min:0',
                'garanties_optionnelles' => 'sometimes|numeric|min:0',
                'accessoires_police' => 'sometimes|numeric|min:0',
                'prime_nette' => 'sometimes|numeric|min:0',
                'taxes_tuca' => 'sometimes|numeric|min:0',
                'prime_ttc' => 'sometimes|numeric|min:0',
                'motif_renouvellement' => 'sometimes|string|in:' . implode(',', array_keys(ContractRenewal::MOTIFS_RENOUVELLEMENT)),
                'observations' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $renewal->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Renouvellement mis à jour avec succès',
                'data' => $renewal->load(['contrat', 'vehicule', 'compagnie'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du renouvellement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un renouvellement
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $renewal = ContractRenewal::byUser($user->id)->findOrFail($id);

            // Vérifier que le renouvellement peut être supprimé
            if (!$this->renewalService->peutEtreSupprime($renewal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce renouvellement ne peut pas être supprimé'
                ], 400);
            }

            $renewal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Renouvellement supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du renouvellement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approuver un renouvellement (pour les compagnies)
     */
    public function approve($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $renewal = ContractRenewal::findOrFail($id);

            // Vérifier que l'utilisateur est de la compagnie
            if ($renewal->compagnie->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à approuver ce renouvellement'
                ], 403);
            }

            DB::beginTransaction();

            $renewal->update([
                'statut' => 'approuve',
                'date_renouvellement' => now()
            ]);

            // Créer le nouveau contrat
            $nouveauContrat = $this->renewalService->creerNouveauContrat($renewal);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Renouvellement approuvé avec succès',
                'data' => [
                    'renewal' => $renewal,
                    'nouveau_contrat' => $nouveauContrat
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'approbation du renouvellement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les contrats éligibles au renouvellement
     */
    public function getEligibleContracts(): JsonResponse
    {
        try {
            $user = Auth::user();
            $contrats = $this->renewalService->getContratsEligibles($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Contrats éligibles au renouvellement récupérés avec succès',
                'data' => $contrats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des contrats éligibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer la prime de renouvellement
     */
    public function calculateRenewalPremium(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'contrat_id' => 'required|exists:insurance_contracts,id',
                'periode_police' => 'required|integer|in:1,3,6,12',
                'garanties_selectionnees' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contrat = Contrat::findOrFail($request->contrat_id);
            $user = Auth::user();

            if ($contrat->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à accéder à ce contrat'
                ], 403);
            }

            $prime = $this->renewalService->calculerPrimeRenouvellement(
                $contrat,
                $request->periode_police,
                $request->garanties_selectionnees
            );

            return response()->json([
                'success' => true,
                'message' => 'Prime de renouvellement calculée avec succès',
                'data' => $prime
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul de la prime de renouvellement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
