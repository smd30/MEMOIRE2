<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devis;
use App\Models\Compagnie;
use App\Models\Garantie;
use App\Models\Vehicule;
use App\Services\DevisCalculationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DevisController extends Controller
{
    /**
     * Afficher la liste des devis
     */
    public function index()
    {
        $devis = Devis::with(['compagnie', 'vehicule', 'client'])
            ->where('client_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $devis
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
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
                'message' => 'Erreur: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Obtenir les garanties d'une compagnie
     */
    public function getGarantiesCompagnie($compagnieId)
    {
        $garanties = Garantie::where('compagnie_id', $compagnieId)
            ->where('statut', 'active')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $garanties
        ]);
    }

    /**
     * Calculer un devis
     */
    public function calculer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicule' => 'required|array',
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
            $calculationService = new DevisCalculationService();
            
            $devisCalcule = $calculationService->calculerDevis(
                $request->vehicule,
                $request->compagnie_id,
                $request->periode_police,
                $request->garanties_selectionnees
            );

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
    public function store(Request $request)
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

        // Créer ou récupérer le véhicule
        $vehicule = $this->creerOuRecupererVehicule($request->vehicule, $request->carte_grise);

        // Simuler le devis pour obtenir le montant
        $simulation = $this->simulerDevis($request->vehicule, $request->compagnie_id, $request->periode_police, $request->garanties_selectionnees);

        // Créer le devis
        $devis = Devis::create([
            'montant' => $simulation['total'],
            'statut' => Devis::STATUT_EN_ATTENTE,
            'client_id' => auth()->id(),
            'compagnie_id' => $request->compagnie_id,
            'vehicule_id' => $vehicule->id,
            'periode_police' => $request->periode_police,
            'date_debut' => $request->date_debut,
            'garanties_selectionnees' => $request->garanties_selectionnees,
            'calcul_details' => $simulation['details'],
            'date_creation' => now(),
            'date_expiration' => now()->addDays(30)
        ]);

        return response()->json([
            'success' => true,
            'data' => $devis->load(['compagnie', 'vehicule'])
        ]);
    }

    /**
     * Souscrire un devis (créer un contrat)
     */
    public function souscrire(Request $request)
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

        // Créer ou récupérer le véhicule
        $vehicule = $this->creerOuRecupererVehicule($request->vehicule, $request->carte_grise);

        // Calculer le devis pour obtenir le montant
        $calculationService = new DevisCalculationService();
        $calcul = $calculationService->calculerDevis(
            $request->vehicule,
            $request->compagnie_id,
            $request->periode_police,
            $request->garanties_selectionnees
        );

        // Créer le devis avec statut accepté
        $devis = Devis::create([
            'montant' => $calcul['total'],
            'statut' => Devis::STATUT_ACCEPTE, // Directement accepté
            'client_id' => auth()->id(),
            'compagnie_id' => $request->compagnie_id,
            'vehicule_id' => $vehicule->id,
            'periode_police' => $request->periode_police,
            'date_debut' => $request->date_debut,
            'garanties_selectionnees' => $request->garanties_selectionnees,
            'calcul_details' => $calcul['details'],
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
    }

    /**
     * Afficher un devis spécifique
     */
    public function show(Devis $devis)
    {
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
    }

    /**
     * Accepter un devis
     */
    public function accepter(Devis $devis)
    {
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
    }

    /**
     * Rejeter un devis
     */
    public function rejeter(Devis $devis)
    {
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
    }

    /**
     * Supprimer un devis
     */
    public function destroy(Devis $devis)
    {
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
    }

    /**
     * Calculer le montant d'une garantie
     */
    private function calculerMontantGarantie($garantie, $vehicule, $periodePolice)
    {
        $config = $garantie->tarification_config;
        
        switch ($garantie->tarification_type) {
            case 'fixe':
                return $config['montant'] ?? 0;
                
            case 'pourcentage':
                $taux = $config['taux'] ?? 0;
                $valeurVehicule = $vehicule['valeur_vehicule'] ?? 0;
                return $valeurVehicule * $taux;
                
            case 'forfait':
                return $config['forfait'] ?? 0;
                
            default:
                return 0;
        }
    }

    /**
     * Simuler un devis
     */
    private function simulerDevis($vehicule, $compagnieId, $periodePolice, $garantiesSelectionnees)
    {
        $garanties = Garantie::where('compagnie_id', $compagnieId)
            ->whereIn('name', $garantiesSelectionnees)
            ->get();

        $total = 0;
        $details = [];

        foreach ($garanties as $garantie) {
            $montant = $this->calculerMontantGarantie($garantie, $vehicule, $periodePolice);
            $total += $montant;
            
            $details[] = [
                'garantie' => $garantie->display_name,
                'type' => $garantie->tarification_type,
                'montant' => $montant,
                'config' => $garantie->tarification_config
            ];
        }

        return [
            'total' => $total,
            'details' => $details
        ];
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
