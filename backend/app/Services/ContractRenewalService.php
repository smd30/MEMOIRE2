<?php

namespace App\Services;

use App\Models\ContractRenewal;
use App\Models\Contrat;
use App\Models\Vehicule;
use App\Models\Compagnie;
use App\Models\Garantie;
use App\Models\Accessoire;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractRenewalService
{
    /**
     * Vérifier si un contrat peut être renouvelé
     */
    public function peutEtreRenouvele(Contrat $contrat): bool
    {
        // Le contrat doit être actif
        if (!$contrat->estActif()) {
            return false;
        }

        // Le contrat doit être proche de l'expiration (30 jours avant)
        $joursRestants = $contrat->getJoursRestantsAttribute();
        
        // Peut être renouvelé 30 jours avant l'expiration
        return $joursRestants <= 30 && $joursRestants > 0;
    }

    /**
     * Vérifier si un renouvellement peut être modifié
     */
    public function peutEtreModifie(ContractRenewal $renewal): bool
    {
        return $renewal->estEnAttente();
    }

    /**
     * Vérifier si un renouvellement peut être supprimé
     */
    public function peutEtreSupprime(ContractRenewal $renewal): bool
    {
        return $renewal->estEnAttente();
    }

    /**
     * Calculer les dates de renouvellement
     */
    public function calculerDatesRenouvellement(Contrat $contrat, int $periodeMois): array
    {
        $dateDebut = $contrat->date_fin->addDay(); // Le lendemain de l'expiration
        $dateFin = $dateDebut->copy()->addMonths($periodeMois);

        return [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ];
    }

    /**
     * Calculer la prime de renouvellement
     */
    public function calculerPrimeRenouvellement(Contrat $contrat, int $periodeMois, array $garantiesSelectionnees): array
    {
        $vehicule = $contrat->vehicule;
        $compagnie = $contrat->compagnie;

        // Prime RC de base (selon la puissance fiscale)
        $primeRC = $this->calculerPrimeRC($vehicule, $compagnie, $periodeMois);

        // Garanties optionnelles
        $garantiesOptionnelles = $this->calculerGarantiesOptionnelles($garantiesSelectionnees, $vehicule, $periodeMois);

        // Accessoires
        $accessoires = $this->calculerAccessoires($vehicule, $periodeMois);

        // Prime nette
        $primeNette = $primeRC + $garantiesOptionnelles + $accessoires;

        // Taxes TUCA (10%)
        $taxesTUCA = $primeNette * 0.10;

        // Prime TTC
        $primeTTC = $primeNette + $taxesTUCA;

        // Évolution par rapport à l'ancienne prime
        $evolution = $this->calculerEvolutionPrime($contrat->prime_ttc, $primeTTC);

        return [
            'prime_rc' => $primeRC,
            'garanties_optionnelles' => $garantiesOptionnelles,
            'accessoires_police' => $accessoires,
            'prime_nette' => $primeNette,
            'taxes_tuca' => $taxesTUCA,
            'prime_ttc' => $primeTTC,
            'prime_precedente' => $contrat->prime_ttc,
            'evolution_prime' => $evolution['montant'],
            'pourcentage_evolution' => $evolution['pourcentage'],
            'type_evolution' => $evolution['type']
        ];
    }

    /**
     * Calculer la prime RC selon la puissance fiscale
     */
    private function calculerPrimeRC(Vehicule $vehicule, Compagnie $compagnie, int $periodeMois): float
    {
        // Tarifs RC selon la puissance fiscale (par mois)
        $tarifsRC = [
            1 => 15000,   // 1 CV
            2 => 18000,   // 2 CV
            3 => 22000,   // 3 CV
            4 => 25000,   // 4 CV
            5 => 28000,   // 5 CV
            6 => 32000,   // 6 CV
            7 => 35000,   // 7 CV
            8 => 38000,   // 8 CV
            9 => 42000,   // 9 CV
            10 => 45000,  // 10 CV
            11 => 48000,  // 11 CV
            12 => 52000,  // 12 CV
            13 => 55000,  // 13 CV
            14 => 58000,  // 14 CV
            15 => 62000,  // 15 CV
            16 => 65000,  // 16 CV
            17 => 68000,  // 17 CV
        ];

        $puissance = $vehicule->puissance_fiscale;
        $tarifBase = $tarifsRC[min($puissance, 17)] ?? 70000; // 17+ CV

        // Coefficient selon la période
        $coefficientPeriode = [
            1 => 1.0,
            3 => 2.8,
            6 => 5.5,
            12 => 10.0
        ];

        return $tarifBase * $coefficientPeriode[$periodeMois];
    }

    /**
     * Calculer les garanties optionnelles
     */
    private function calculerGarantiesOptionnelles(array $garantiesSelectionnees, Vehicule $vehicule, int $periodeMois): float
    {
        $total = 0;

        foreach ($garantiesSelectionnees as $garantieId) {
            $garantie = Garantie::find($garantieId);
            if ($garantie) {
                $tarif = $this->calculerTarifGarantie($garantie, $vehicule, $periodeMois);
                $total += $tarif;
            }
        }

        return $total;
    }

    /**
     * Calculer le tarif d'une garantie
     */
    private function calculerTarifGarantie(Garantie $garantie, Vehicule $vehicule, int $periodeMois): float
    {
        $tarifBase = $garantie->tarif_base ?? 0;
        
        // Coefficient selon la valeur du véhicule
        $coefficientValeur = $this->getCoefficientValeur($vehicule->valeur_vehicule);
        
        // Coefficient selon l'âge du véhicule
        $coefficientAge = $this->getCoefficientAge($vehicule->age_vehicule);
        
        // Coefficient selon la période
        $coefficientPeriode = [
            1 => 1.0,
            3 => 2.8,
            6 => 5.5,
            12 => 10.0
        ];

        return $tarifBase * $coefficientValeur * $coefficientAge * $coefficientPeriode[$periodeMois];
    }

    /**
     * Calculer les accessoires
     */
    private function calculerAccessoires(Vehicule $vehicule, int $periodeMois): float
    {
        $total = 0;

        // Accessoires fixes
        $accessoires = Accessoire::where('actif', true)->get();
        
        foreach ($accessoires as $accessoire) {
            $tarif = $accessoire->tarif ?? 0;
            
            // Coefficient selon la période
            $coefficientPeriode = [
                1 => 1.0,
                3 => 2.8,
                6 => 5.5,
                12 => 10.0
            ];

            $total += $tarif * $coefficientPeriode[$periodeMois];
        }

        return $total;
    }

    /**
     * Calculer l'évolution de la prime
     */
    private function calculerEvolutionPrime(float $primePrecedente, float $primeNouvelle): array
    {
        $evolution = $primeNouvelle - $primePrecedente;
        $pourcentage = ($evolution / $primePrecedente) * 100;

        $type = 'stable';
        if ($pourcentage > 5) {
            $type = 'augmentation';
        } elseif ($pourcentage < -5) {
            $type = 'diminution';
        }

        return [
            'montant' => $evolution,
            'pourcentage' => $pourcentage,
            'type' => $type
        ];
    }

    /**
     * Obtenir le coefficient selon la valeur du véhicule
     */
    private function getCoefficientValeur(float $valeur): float
    {
        if ($valeur <= 1000000) return 1.0;
        if ($valeur <= 2000000) return 1.2;
        if ($valeur <= 5000000) return 1.5;
        if ($valeur <= 10000000) return 2.0;
        return 2.5;
    }

    /**
     * Obtenir le coefficient selon l'âge du véhicule
     */
    private function getCoefficientAge(int $age): float
    {
        if ($age <= 2) return 1.0;
        if ($age <= 5) return 1.1;
        if ($age <= 10) return 1.3;
        if ($age <= 15) return 1.5;
        return 2.0;
    }

    /**
     * Obtenir les contrats éligibles au renouvellement
     */
    public function getContratsEligibles(int $userId): array
    {
        $contrats = Contrat::with(['vehicule', 'compagnie'])
            ->where('user_id', $userId)
            ->actif()
            ->get();

        $eligibles = [];

        foreach ($contrats as $contrat) {
            if ($this->peutEtreRenouvele($contrat)) {
                $eligibles[] = [
                    'contrat' => $contrat,
                    'jours_restants' => $contrat->getJoursRestantsAttribute(),
                    'peut_renouveler' => true
                ];
            }
        }

        return $eligibles;
    }

    /**
     * Créer un nouveau contrat à partir d'un renouvellement approuvé
     */
    public function creerNouveauContrat(ContractRenewal $renewal): Contrat
    {
        $nouveauContrat = Contrat::create([
            'user_id' => $renewal->user_id,
            'vehicule_id' => $renewal->vehicule_id,
            'compagnie_id' => $renewal->compagnie_id,
            'numero_police' => $renewal->numero_police_nouveau,
            'numero_attestation' => $renewal->numero_attestation_nouveau,
            'cle_securite' => $renewal->cle_securite_nouveau,
            'date_debut' => $renewal->date_debut_nouveau,
            'date_fin' => $renewal->date_fin_nouveau,
            'periode_police' => $renewal->periode_police,
            'garanties_selectionnees' => $renewal->garanties_selectionnees,
            'prime_rc' => $renewal->prime_rc,
            'garanties_optionnelles' => $renewal->garanties_optionnelles,
            'accessoires_police' => $renewal->accessoires_police,
            'prime_nette' => $renewal->prime_nette,
            'taxes_tuca' => $renewal->taxes_tuca,
            'prime_ttc' => $renewal->prime_ttc,
            'statut' => 'actif',
            'date_souscription' => now()
        ]);

        // Mettre à jour le statut du renouvellement
        $renewal->update(['statut' => 'renouvele']);

        // Mettre à jour le statut de l'ancien contrat
        $ancienContrat = $renewal->contrat;
        $ancienContrat->update(['statut' => 'expire']);

        return $nouveauContrat;
    }

    /**
     * Obtenir les statistiques des renouvellements
     */
    public function getStatistiques(int $userId): array
    {
        $totalRenewals = ContractRenewal::byUser($userId)->count();
        $enAttente = ContractRenewal::byUser($userId)->enAttente()->count();
        $approuves = ContractRenewal::byUser($userId)->approuve()->count();
        $renouveles = ContractRenewal::byUser($userId)->renouvele()->count();
        $rejetes = ContractRenewal::byUser($userId)->rejete()->count();

        // Évolution moyenne des primes
        $evolutionMoyenne = ContractRenewal::byUser($userId)
            ->whereNotNull('pourcentage_evolution')
            ->avg('pourcentage_evolution');

        return [
            'total_renouvellements' => $totalRenewals,
            'en_attente' => $enAttente,
            'approuves' => $approuves,
            'renouveles' => $renouveles,
            'rejetes' => $rejetes,
            'evolution_moyenne_prime' => round($evolutionMoyenne ?? 0, 2),
            'taux_approbation' => $totalRenewals > 0 ? round(($approuves + $renouveles) / $totalRenewals * 100, 2) : 0
        ];
    }
}
