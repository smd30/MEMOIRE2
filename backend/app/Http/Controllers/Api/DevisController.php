<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DevisService;
use App\Models\Devis;
use App\Models\Compagnie;
use App\Models\Garantie;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DevisController extends Controller
{
    protected $devisService;

    public function __construct(DevisService $devisService)
    {
        $this->devisService = $devisService;
    }

    /**
     * Afficher la liste des devis
     */
    public function index(): JsonResponse
    {
        try {
            // Pour les tests, retourner une liste vide si pas d'authentification
            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Aucun devis trouvé (mode test)'
                ]);
            }

            // Essayer de récupérer les devis sans les relations problématiques
            $devis = Devis::select('id', 'montant', 'statut', 'client_id', 'compagnie_id', 'vehicule_id', 'periode_police', 'date_debut', 'date_creation', 'date_expiration')
                ->where('client_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $devis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(): JsonResponse
    {
        try {
            $compagnies = Compagnie::where('is_active', true)->get();
            $periodes = Devis::PERIODES;

            return response()->json([
                'success' => true,
                'data' => [
                    'compagnies' => $compagnies,
                    'periodes' => $periodes
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les garanties d'une compagnie
     */
    public function getGarantiesCompagnie($compagnieId): JsonResponse
    {
        try {
            $garanties = Garantie::where('compagnie_id', $compagnieId)
                ->where('statut', 'active')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $garanties
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des garanties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer un devis
     */
    public function calculer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicule' => 'required|array',
            'vehicule.categorie' => 'required|string|in:C1,C2,C3,C5',
            'vehicule.puissance_fiscale' => 'required|integer|min:1',
            'vehicule.energie' => 'required|string|in:essence,diesel,gaz,electrique,hybride',
            'compagnie_id' => 'required|exists:compagnies,id',
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

        try {
            // Utiliser le nouveau service de calcul
            $devisCalcule = $this->devisService->calculerDevis([
                'categorie' => $request->vehicule['categorie'] ?? 'C1',
                'caracteristiques' => [
                    'puissance' => $request->vehicule['puissance_fiscale'] ?? 0,
                    'tonnage' => $request->vehicule['tonnage'] ?? 0,
                    'carburant' => $request->vehicule['energie'] ?? 'essence'
                ],
                'garanties' => array_fill_keys($request->garanties_selectionnees, true),
                'periode_police' => $request->periode_police
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Devis calculé avec succès',
                'data' => $devisCalcule
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau devis
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicule' => 'required|array',
            'compagnie_id' => 'required|exists:compagnies,id',
            'periode_police' => 'required|integer|in:1,3,6,12',
            'date_debut' => 'required|date',
            'garanties_selectionnees' => 'required|array',
            'carte_grise' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Créer ou récupérer le véhicule
            $vehicule = $this->creerOuRecupererVehicule($request->vehicule, $request->carte_grise);

            // Calculer le devis avec le nouveau service
            $simulation = $this->devisService->calculerDevis([
                'categorie' => $request->vehicule['categorie'] ?? 'C1',
                'caracteristiques' => [
                    'puissance' => $request->vehicule['puissance_fiscale'] ?? 0,
                    'tonnage' => $request->vehicule['tonnage'] ?? 0,
                    'carburant' => $request->vehicule['energie'] ?? 'essence'
                ],
                'garanties' => array_fill_keys($request->garanties_selectionnees, true),
                'periode_police' => $request->periode_police
            ]);

            // Créer le devis
            $devis = Devis::create([
                'montant' => $simulation['prime_ttc'],
                'statut' => Devis::STATUT_EN_ATTENTE,
                'client_id' => auth()->id(),
                'compagnie_id' => $request->compagnie_id,
                'vehicule_id' => $vehicule->id,
                'periode_police' => $request->periode_police,
                'date_debut' => $request->date_debut,
                'garanties_selectionnees' => $request->garanties_selectionnees,
                'calcul_details' => $simulation,
                'date_creation' => now(),
                'date_expiration' => now()->addDays(30)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Devis créé avec succès',
                'data' => $devis->load(['compagnie', 'vehicule'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Souscrire un devis (créer un contrat)
     */
    public function souscrire(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicule' => 'required|array',
            'compagnie_id' => 'required|exists:compagnies,id',
            'periode_police' => 'required|integer|in:1,3,6,12',
            'date_debut' => 'required|date',
            'garanties_selectionnees' => 'required|array',
            'carte_grise' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Créer ou récupérer le véhicule
            $vehicule = $this->creerOuRecupererVehicule($request->vehicule, $request->carte_grise);

            // Calculer le devis avec le nouveau service
            $calcul = $this->devisService->calculerDevis([
                'categorie' => $request->vehicule['categorie'] ?? 'C1',
                'caracteristiques' => [
                    'puissance' => $request->vehicule['puissance_fiscale'] ?? 0,
                    'tonnage' => $request->vehicule['tonnage'] ?? 0,
                    'carburant' => $request->vehicule['energie'] ?? 'essence'
                ],
                'garanties' => array_fill_keys($request->garanties_selectionnees, true),
                'periode_police' => $request->periode_police
            ]);

            // Créer le devis avec statut accepté
            $devis = Devis::create([
                'montant' => $calcul['prime_ttc'],
                'statut' => Devis::STATUT_ACCEPTE, // Directement accepté
                'client_id' => auth()->id(),
                'compagnie_id' => $request->compagnie_id,
                'vehicule_id' => $vehicule->id,
                'periode_police' => $request->periode_police,
                'date_debut' => $request->date_debut,
                'garanties_selectionnees' => $request->garanties_selectionnees,
                'calcul_details' => $calcul,
                'date_creation' => now(),
                'date_expiration' => now()->addDays(30)
            ]);

            // Créer automatiquement le contrat
            $contrat = $devis->creerContrat();

            return response()->json([
                'success' => true,
                'message' => 'Devis souscrit avec succès ! Votre contrat est maintenant actif.',
                'data' => [
                    'devis' => $devis->load(['compagnie', 'vehicule']),
                    'contrat' => $contrat
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la souscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un devis spécifique
     */
    public function show(Devis $devis): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur peut voir ce devis
            if ($devis->client_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $devis->load(['compagnie', 'vehicule', 'client'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accepter un devis
     */
    public function accepter(Devis $devis): JsonResponse
    {
        try {
            if ($devis->client_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $devis->accepter();

            return response()->json([
                'success' => true,
                'message' => 'Devis accepté avec succès',
                'data' => $devis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'acceptation du devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rejeter un devis
     */
    public function rejeter(Devis $devis): JsonResponse
    {
        try {
            if ($devis->client_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $devis->rejeter();

            return response()->json([
                'success' => true,
                'message' => 'Devis rejeté',
                'data' => $devis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rejet du devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un devis
     */
    public function destroy(Devis $devis): JsonResponse
    {
        try {
            if ($devis->client_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $devis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Devis supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcule un devis d'assurance automobile (nouveau système)
     */
    public function calculerDevis(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $request->validate([
                'categorie' => 'required|string|in:C1,C2,C3,C5',
                'caracteristiques' => 'required|array',
                'caracteristiques.puissance' => 'required|integer|min:1',
                'caracteristiques.tonnage' => 'nullable|numeric|min:0',
                'caracteristiques.carburant' => 'nullable|string|in:essence,diesel',
                'caracteristiques.type' => 'nullable|string|in:cyclomoteurs,scooters_125,motos_125+,side_cars',
                'garanties' => 'nullable|array',
                'garanties.*' => 'boolean',
                'accessoires' => 'nullable|array',
                'accessoires.*' => 'numeric|min:0'
            ]);

            // Calcul du devis
            $devis = $this->devisService->calculerDevis($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Devis calculé avec succès',
                'data' => $devis
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de paramètres: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul du devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtient les catégories disponibles
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = $this->devisService->getCategories();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des catégories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtient les garanties disponibles
     */
    public function getGaranties(): JsonResponse
    {
        try {
            $garanties = $this->devisService->getGaranties();

            return response()->json([
                'success' => true,
                'data' => $garanties
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des garanties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtient les informations complètes pour le formulaire de devis
     */
    public function getInformationsDevis(): JsonResponse
    {
        try {
            $categories = $this->devisService->getCategories();
            $garanties = $this->devisService->getGaranties();

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'garanties' => $garanties,
                    'informations' => [
                        'taux_taxe' => '19%',
                        'devise' => 'FCFA',
                        'note' => 'Les tarifs sont basés sur le barème DIOTALI officiel du Sénégal'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des informations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exemple de devis pour démonstration
     */
    public function exempleDevis(): JsonResponse
    {
        try {
            // Exemple: Voiture particulière 7 CV avec garanties
            $exemple = [
                'categorie' => 'C1',
                'caracteristiques' => [
                    'puissance' => 7
                ],
                'garanties' => [
                    'vol' => true,
                    'incendie' => true,
                    'bris_glace' => true,
                    'defense_recours' => true,
                    'individuelle_conducteur' => false,
                    'dommages_tous_accidents' => false
                ]
            ];

            $devis = $this->devisService->calculerDevis($exemple);

            return response()->json([
                'success' => true,
                'message' => 'Exemple de devis calculé',
                'exemple' => $exemple,
                'devis' => $devis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul de l\'exemple: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer ou récupérer un véhicule
     */
    private function creerOuRecupererVehicule($vehiculeData, $carteGrise = null)
    {
        // Vérifier si le véhicule existe déjà
        $vehicule = Vehicule::where('immatriculation', $vehiculeData['immatriculation'])
            ->where('user_id', auth()->id())
            ->first();

        if ($vehicule) {
            return $vehicule;
        }

        // Traiter la carte grise si fournie
        $carteGrisePath = null;
        if ($carteGrise) {
            $carteGrisePath = $carteGrise->store('cartes_grise', 'public');
        }

        // Créer le nouveau véhicule
        $vehicule = Vehicule::create([
            'marque_vehicule' => $vehiculeData['marque_vehicule'],
            'modele' => $vehiculeData['modele'],
            'immatriculation' => $vehiculeData['immatriculation'],
            'categorie' => $vehiculeData['categorie'],
            'puissance_fiscale' => $vehiculeData['puissance_fiscale'],
            'date_mise_en_circulation' => $vehiculeData['date_mise_en_circulation'],
            'valeur_vehicule' => $vehiculeData['valeur_vehicule'],
            'valeur_venale' => $vehiculeData['valeur_venale'],
            'numero_chassis' => $vehiculeData['numero_chassis'],
            'energie' => $vehiculeData['energie'],
            'places' => $vehiculeData['places'],
            'proprietaire_nom' => $vehiculeData['proprietaire_nom'],
            'proprietaire_prenom' => $vehiculeData['proprietaire_prenom'],
            'proprietaire_adresse' => $vehiculeData['proprietaire_adresse'],
            'proprietaire_telephone' => $vehiculeData['proprietaire_telephone'],
            'proprietaire_email' => $vehiculeData['proprietaire_email'],
            'carte_grise' => $carteGrisePath,
            'user_id' => auth()->id()
        ]);

        return $vehicule;
    }
}
