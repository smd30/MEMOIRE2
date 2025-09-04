<?php

namespace App\Services;

use App\Models\Compagnie;
use App\Models\Garantie;
use App\Models\Vehicule;

class DevisCalculationService
{
    // Tarifs de base RC par catégorie (FCFA/an)
    private const TARIFS_RC_BASE = [
        'vehicules_particuliers' => 25000,
        'transport_commun' => 35000,
        'transport_marchandises' => 45000,
        'deux_trois_roues' => 15000,
        'vehicules_speciaux' => 60000,
        'vehicules_etat' => 40000
    ];

    // Pourcentages des garanties optionnelles (% de la valeur assurée)
    private const POURCENTAGES_GARANTIES = [
        'vol' => 2.0,
        'incendie' => 1.0,
        'bris_glace' => 0.5,
        'dommages_collision' => 3.0,
        'defense_recours' => 0.3,
        'assistance_0km' => 0.2,
        'personnes_transportees' => 0.8,
        'bagages' => 0.1
    ];

    // Taxe sur les Primes d'Assurance (TPA) au Sénégal
    private const TPA_POURCENTAGE = 10.0;

    // Frais de dossier
    private const FRAIS_DOSSIER = 5000;

    /**
     * Calculer le devis complet
     */
    public function calculerDevis(array $vehicule, int $compagnieId, int $periodePolice, array $garantiesSelectionnees): array
    {
        // Récupérer la compagnie
        $compagnie = Compagnie::find($compagnieId);
        if (!$compagnie) {
            throw new \Exception('Compagnie non trouvée');
        }

        // Récupérer les garanties sélectionnées
        $garanties = Garantie::where('compagnie_id', $compagnieId)
            ->whereIn('id', $garantiesSelectionnees)
            ->get();

        // Calculer la prime de base RC
        $primeRC = $this->calculerPrimeRC($vehicule['categorie'], $compagnie);

        // Calculer les primes des garanties optionnelles
        $primesGaranties = $this->calculerPrimesGaranties($garanties, $vehicule['valeur_vehicule']);

        // Calculer le sous-total
        $sousTotal = $primeRC + $primesGaranties['total'];

        // Calculer les taxes
        $taxes = $this->calculerTaxes($sousTotal);

        // Calculer le total
        $total = $sousTotal + $taxes + self::FRAIS_DOSSIER;

        // Ajuster selon la période
        $totalAjuste = $this->ajusterSelonPeriode($total, $periodePolice);

        return [
            'compagnie_id' => $compagnieId,
            'periode_police' => $periodePolice,
            'garanties_selectionnees' => $garantiesSelectionnees,
            'montant' => $totalAjuste,
            'details' => [
                'prime_rc' => $primeRC,
                'primes_garanties' => $primesGaranties['details'],
                'sous_total' => $sousTotal,
                'taxes' => $taxes,
                'frais_dossier' => self::FRAIS_DOSSIER,
                'total_annuel' => $total,
                'total_periode' => $totalAjuste
            ]
        ];
    }

    /**
     * Calculer la prime de base RC
     */
    private function calculerPrimeRC(string $categorie, Compagnie $compagnie): float
    {
        $tarifBase = self::TARIFS_RC_BASE[$categorie] ?? self::TARIFS_RC_BASE['vehicules_particuliers'];
        
        // Ajuster selon le coefficient de la compagnie
        $coefficientCompagnie = $compagnie->coefficient_tarif ?? 1.0;
        
        return $tarifBase * $coefficientCompagnie;
    }

    /**
     * Calculer les primes des garanties optionnelles
     */
    private function calculerPrimesGaranties($garanties, float $valeurVehicule): array
    {
        $total = 0;
        $details = [];

        foreach ($garanties as $garantie) {
            $pourcentage = self::POURCENTAGES_GARANTIES[$garantie->name] ?? 0.5;
            $prime = ($valeurVehicule * $pourcentage) / 100;
            
            $details[] = [
                'garantie' => $garantie->display_name,
                'pourcentage' => $pourcentage,
                'prime' => $prime
            ];
            
            $total += $prime;
        }

        return [
            'total' => $total,
            'details' => $details
        ];
    }

    /**
     * Calculer les taxes
     */
    private function calculerTaxes(float $montant): float
    {
        return ($montant * self::TPA_POURCENTAGE) / 100;
    }

    /**
     * Ajuster selon la période de police
     */
    private function ajusterSelonPeriode(float $montantAnnuel, int $periodePolice): float
    {
        // Facteurs d'ajustement selon la période
        $facteurs = [
            1 => 0.12,  // 1 mois = 12% de l'annuel
            3 => 0.30,  // 3 mois = 30% de l'annuel
            6 => 0.55,  // 6 mois = 55% de l'annuel
            12 => 1.0   // 12 mois = 100% de l'annuel
        ];

        $facteur = $facteurs[$periodePolice] ?? 1.0;
        return $montantAnnuel * $facteur;
    }

    /**
     * Obtenir les tarifs de base pour affichage
     */
    public function getTarifsBase(): array
    {
        return [
            'rc_base' => self::TARIFS_RC_BASE,
            'pourcentages_garanties' => self::POURCENTAGES_GARANTIES,
            'tpa' => self::TPA_POURCENTAGE,
            'frais_dossier' => self::FRAIS_DOSSIER
        ];
    }
}


