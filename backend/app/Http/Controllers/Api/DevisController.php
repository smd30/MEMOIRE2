<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Garantie;
use App\Models\TarifCategory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class DevisController extends Controller
{
    /**
     * Calculer un devis sans le sauvegarder
     */
    public function calculate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_info.category' => 'required|string',
            'vehicle_info.sub_category' => 'nullable|string',
            'vehicle_info.power_fiscal' => 'required|integer|min:1|max:50',
            'vehicle_info.year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'selected_garanties' => 'required|array|min:1',
            'selected_garanties.*' => 'string|exists:garanties,name',
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicleInfo = $request->input('vehicle_info');
            $selectedGaranties = $request->input('selected_garanties');
            $durationMonths = $request->input('duration_months');

            // Calculer la prime de base
            $basePremium = $this->calculateBasePremium($vehicleInfo, $durationMonths);
            
            // Calculer les coefficients des garanties
            $garantieCoefficients = $this->calculateGarantieCoefficients($selectedGaranties);
            
            // Calculer la prime totale
            $garantiesPremium = $basePremium * $garantieCoefficients['total_coefficient'];
            $taxes = ($basePremium + $garantiesPremium) * config('app.default_tax_rate', 0.20);
            $totalPremium = $basePremium + $garantiesPremium + $taxes;

            // Générer un numéro de devis temporaire
            $quoteNumber = 'TEMP-' . date('YmdHis') . '-' . rand(1000, 9999);

            $devis = [
                'quote_number' => $quoteNumber,
                'vehicle_info' => $vehicleInfo,
                'selected_garanties' => $selectedGaranties,
                'duration_months' => $durationMonths,
                'base_premium' => round($basePremium, 2),
                'garanties_premium' => round($garantiesPremium, 2),
                'taxes' => round($taxes, 2),
                'total_premium' => round($totalPremium, 2),
                'monthly_premium' => round($totalPremium / $durationMonths, 2),
                'garanties_details' => $garantieCoefficients['details'],
                'expires_at' => now()->addDays(30),
            ];

            // Optionnel : Envoyer à la Company API pour validation
            if (config('app.company_api_enabled', true)) {
                try {
                    $companyApiResponse = Http::timeout(10)->post(config('app.company_api_url') . '/api/quote', [
                        'vehicle_info' => $vehicleInfo,
                        'selected_garanties' => $selectedGaranties,
                        'duration_months' => $durationMonths,
                    ]);

                    if ($companyApiResponse->successful()) {
                        $companyData = $companyApiResponse->json();
                        $devis['company_validation'] = $companyData['data'] ?? null;
                    }
                } catch (\Exception $e) {
                    // Log l'erreur mais continue sans validation externe
                    \Log::warning('Erreur lors de la validation Company API: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Devis calculé avec succès',
                'data' => $devis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul du devis',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer et sauvegarder un devis
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_info.category' => 'required|string',
            'vehicle_info.sub_category' => 'nullable|string',
            'vehicle_info.power_fiscal' => 'required|integer|min:1|max:50',
            'vehicle_info.year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_info.brand' => 'required|string',
            'vehicle_info.model' => 'required|string',
            'selected_garanties' => 'required|array|min:1',
            'selected_garanties.*' => 'string|exists:garanties,name',
            'duration_months' => 'required|integer|min:1|max:12',
            'client_name' => 'required|string',
            'client_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicleInfo = $request->input('vehicle_info');
            $selectedGaranties = $request->input('selected_garanties');
            $durationMonths = $request->input('duration_months');

            // Calculer la prime de base
            $basePremium = $this->calculateBasePremium($vehicleInfo, $durationMonths);
            
            // Calculer les coefficients des garanties
            $garantieCoefficients = $this->calculateGarantieCoefficients($selectedGaranties);
            
            // Calculer la prime totale
            $garantiesPremium = $basePremium * $garantieCoefficients['total_coefficient'];
            $taxes = ($basePremium + $garantiesPremium) * config('app.default_tax_rate', 0.20);
            $totalPremium = $basePremium + $garantiesPremium + $taxes;

            // Créer le devis
            $devis = Devis::create([
                'user_id' => auth()->id(),
                'quote_number' => 'CQ-' . date('YmdHis') . '-' . rand(1000, 9999),
                'vehicle_info' => $vehicleInfo,
                'selected_garanties' => $selectedGaranties,
                'duration_months' => $durationMonths,
                'base_premium' => round($basePremium, 2),
                'total_premium' => round($totalPremium, 2),
                'taxes' => round($taxes, 2),
                'status' => 'sent',
                'expires_at' => now()->addDays(30),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Devis créé avec succès',
                'data' => $devis
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du devis',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un devis spécifique
     */
    public function show(Devis $devis): JsonResponse
    {
        // Vérifier l'autorisation
        if (auth()->id() !== $devis->user_id && !auth()->user()->canManageContracts()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $devis
        ]);
    }

    /**
     * Lister les devis
     */
    public function index(Request $request): JsonResponse
    {
        $query = Devis::query();

        // Filtres selon le rôle
        if (auth()->user()->isClient()) {
            $query->where('user_id', auth()->id());
        }

        // Filtres optionnels
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('expired')) {
            if ($request->expired) {
                $query->where('expires_at', '<', now());
            } else {
                $query->where('expires_at', '>=', now());
            }
        }

        $devis = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $devis
        ]);
    }

    /**
     * Obtenir la liste des garanties disponibles
     */
    public function getGaranties(): JsonResponse
    {
        $garanties = Garantie::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'display_name', 'description', 'coefficient', 'is_required']);

        return response()->json([
            'success' => true,
            'data' => $garanties
        ]);
    }

    /**
     * Obtenir la grille tarifaire
     */
    public function getTarifs(): JsonResponse
    {
        $tarifs = TarifCategory::where('is_active', true)
            ->orderBy('name')
            ->orderBy('power_fiscal_min')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tarifs
        ]);
    }

    /**
     * Calculer la prime de base selon la grille tarifaire
     */
    private function calculateBasePremium(array $vehicleInfo, int $durationMonths): float
    {
        $tarif = TarifCategory::where('name', $vehicleInfo['category'])
            ->where('sub_category', $vehicleInfo['sub_category'])
            ->where('power_fiscal_min', '<=', $vehicleInfo['power_fiscal'])
            ->where('power_fiscal_max', '>=', $vehicleInfo['power_fiscal'])
            ->where('is_active', true)
            ->first();

        if (!$tarif) {
            // Tarif par défaut selon la catégorie
            $defaultRates = [
                'Citadine' => 40.00,
                'SUV' => 60.00,
                'Berline' => 50.00,
                'Utilitaire' => 55.00,
                'Moto' => 30.00
            ];

            $defaultRate = $defaultRates[$vehicleInfo['category']] ?? 50.00;
            return $defaultRate * $durationMonths;
        }

        return $tarif->base_rate_monthly * $durationMonths;
    }

    /**
     * Calculer les coefficients des garanties
     */
    private function calculateGarantieCoefficients(array $selectedGaranties): array
    {
        $garanties = Garantie::whereIn('name', $selectedGaranties)
            ->where('is_active', true)
            ->get();

        $totalCoefficient = 0;
        $details = [];

        foreach ($garanties as $garantie) {
            $totalCoefficient += $garantie->coefficient;
            $details[] = [
                'name' => $garantie->name,
                'display_name' => $garantie->display_name,
                'coefficient' => $garantie->coefficient,
                'is_required' => $garantie->is_required,
            ];
        }

        return [
            'total_coefficient' => $totalCoefficient,
            'details' => $details
        ];
    }

    /**
     * Récupérer les compagnies disponibles
     */
    public function getCompagnies(): JsonResponse
    {
        try {
            $compagnies = \App\Models\Compagnie::where('is_active', true)
                ->orderBy('nom')
                ->get(['id', 'nom', 'description']);

            return response()->json([
                'success' => true,
                'data' => $compagnies,
                'message' => 'Compagnies récupérées avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des compagnies',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
