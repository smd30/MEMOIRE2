<?php

namespace App\Services;

class DevisService
{
    // Tarifs RC par catégorie (FCFA)
    private const TARIFS_RC = [
        'C1' => [ // Véhicules de tourisme
            '2CV' => 37601,
            '3-6CV' => 45181,
            '7-10CV' => 51078,
            '11-14CV' => 65677,
            '15-23CV' => 86456,
            '24CV+' => 104143
        ],
        'C2' => [ // Véhicules de commerce
            '2CV_3T5' => 56958,
            '2CV_3T5+' => 91294,
            '3-6CV_3T5' => 67644,
            '3-6CV_3T5+' => 103580,
            '7-10CV_3T5' => 78974,
            '7-10CV_3T5+' => 130415,
            '11-14CV_3T5' => 113944,
            '11-14CV_3T5+' => 170617,
            '15-23CV_3T5' => 146969,
            '15-23CV_3T5+' => 208597,
            '24CV+_3T5' => 174491,
            '24CV+_3T5+' => 240245
        ],
        'C3' => [ // Transports publics de marchandises
            '2CV_3T5' => 115559,
            '2CV_3T5+' => 117937,
            '3-6CV_3T5' => 133056,
            '3-6CV_3T5+' => 135437,
            '7-10CV_3T5' => 165601,
            '7-10CV_3T5+' => 167982,
            '11-14CV_3T5' => 222270,
            '11-14CV_3T5+' => 224650,
            '15-23CV_3T5' => 283130,
            '15-23CV_3T5+' => 285510,
            '24CV+_3T5' => 328955,
            '24CV+_3T5+' => 331336
        ],
        'C5' => [ // Véhicules motorisés 2 ou 3 roues
            'cyclomoteurs' => 18780,
            'scooters_125' => 29448,
            'motos_125+' => 34021,
            'side_cars' => 40880
        ]
    ];

    // Tarifs des garanties optionnelles (FCFA)
    private const TARIFS_GARANTIES = [
        'vol' => 15000, // Prime fixe pour la garantie vol
        'incendie' => 12000,
        'bris_glace' => 10000,
        'defense_recours' => 5000,
        'individuelle_conducteur' => 8000,
        'dommages_tous_accidents' => 25000
    ];

    // Accessoires de police (FCFA)
    private const ACCESSOIRES_POLICE = [
        'frais_gestion' => 5000,
        'carte_brune_cedeao' => 3000,
        'vignette' => 2000
    ];

    // Taux de taxes
    private const TAUX_TUCA = 0.19; // 19%

    /**
     * Calcule la prime RC selon la catégorie et les caractéristiques du véhicule
     */
    public function calculerPrimeRC(string $categorie, array $caracteristiques): float
    {
        if (!isset(self::TARIFS_RC[$categorie])) {
            throw new \InvalidArgumentException("Catégorie non reconnue: {$categorie}");
        }

        $tarifs = self::TARIFS_RC[$categorie];

        switch ($categorie) {
            case 'C1':
                return $this->calculerPrimeRC_C1($caracteristiques, $tarifs);
            case 'C2':
                return $this->calculerPrimeRC_C2($caracteristiques, $tarifs);
            case 'C3':
                return $this->calculerPrimeRC_C3($caracteristiques, $tarifs);
            case 'C5':
                return $this->calculerPrimeRC_C5($caracteristiques, $tarifs);
            default:
                throw new \InvalidArgumentException("Catégorie non supportée: {$categorie}");
        }
    }

    private function calculerPrimeRC_C1(array $caracteristiques, array $tarifs): float
    {
        $puissance = $caracteristiques['puissance'] ?? 0;
        
        if ($puissance <= 2) return $tarifs['2CV'];
        if ($puissance <= 6) return $tarifs['3-6CV'];
        if ($puissance <= 10) return $tarifs['7-10CV'];
        if ($puissance <= 14) return $tarifs['11-14CV'];
        if ($puissance <= 23) return $tarifs['15-23CV'];
        return $tarifs['24CV+'];
    }

    private function calculerPrimeRC_C2(array $caracteristiques, array $tarifs): float
    {
        $puissance = $caracteristiques['puissance'] ?? 0;
        $tonnage = $caracteristiques['tonnage'] ?? 0;
        $carburant = $caracteristiques['carburant'] ?? 'essence';
        
        $cle = $this->getClePuissanceC2($puissance, $carburant);
        $cle .= $tonnage <= 3.5 ? '_3T5' : '_3T5+';
        
        return $tarifs[$cle] ?? $tarifs['3-6CV_3T5'];
    }

    private function calculerPrimeRC_C3(array $caracteristiques, array $tarifs): float
    {
        $puissance = $caracteristiques['puissance'] ?? 0;
        $tonnage = $caracteristiques['tonnage'] ?? 0;
        $carburant = $caracteristiques['carburant'] ?? 'essence';
        
        $cle = $this->getClePuissanceC3($puissance, $carburant);
        $cle .= $tonnage <= 3.5 ? '_3T5' : '_3T5+';
        
        return $tarifs[$cle] ?? $tarifs['3-6CV_3T5'];
    }

    private function calculerPrimeRC_C5(array $caracteristiques, array $tarifs): float
    {
        $type = $caracteristiques['type'] ?? 'motos_125+';
        return $tarifs[$type] ?? $tarifs['motos_125+'];
    }

    private function getClePuissanceC2(int $puissance, string $carburant): string
    {
        if ($carburant === 'diesel') {
            if ($puissance <= 2) return '2CV';
            if ($puissance <= 4) return '3-6CV';
            if ($puissance <= 7) return '7-10CV';
            if ($puissance <= 10) return '11-14CV';
            if ($puissance <= 16) return '15-23CV';
            return '24CV+';
        } else {
            if ($puissance <= 2) return '2CV';
            if ($puissance <= 6) return '3-6CV';
            if ($puissance <= 10) return '7-10CV';
            if ($puissance <= 14) return '11-14CV';
            if ($puissance <= 23) return '15-23CV';
            return '24CV+';
        }
    }

    private function getClePuissanceC3(int $puissance, string $carburant): string
    {
        return $this->getClePuissanceC2($puissance, $carburant);
    }

    /**
     * Calcule les garanties optionnelles
     */
    public function calculerGarantiesOptionnelles(array $garanties): float
    {
        $total = 0;
        
        foreach ($garanties as $garantie => $active) {
            if ($active && isset(self::TARIFS_GARANTIES[$garantie])) {
                $total += self::TARIFS_GARANTIES[$garantie];
            }
        }
        
        return $total;
    }

    /**
     * Calcule les accessoires de police
     */
    public function calculerAccessoiresPolice(array $accessoires = []): float
    {
        $total = 0;
        
        // Accessoires par défaut
        $total += self::ACCESSOIRES_POLICE['frais_gestion'];
        $total += self::ACCESSOIRES_POLICE['carte_brune_cedeao'];
        $total += self::ACCESSOIRES_POLICE['vignette'];
        
        // Accessoires supplémentaires
        foreach ($accessoires as $accessoire => $montant) {
            $total += $montant;
        }
        
        return $total;
    }

    /**
     * Calcule le devis complet
     */
    public function calculerDevis(array $donnees): array
    {
        // Récupérer la période de police (en mois)
        $periodeMois = $donnees['periode_police'] ?? 12;
        
        // Prime RC annuelle
        $primeRCAnnuelle = $this->calculerPrimeRC(
            $donnees['categorie'], 
            $donnees['caracteristiques']
        );
        
        // Prime RC pour la période sélectionnée
        $primeRC = ($primeRCAnnuelle / 12) * $periodeMois;

        // Garanties optionnelles (annuelles)
        $garantiesOptionnellesAnnuelle = $this->calculerGarantiesOptionnelles(
            $donnees['garanties'] ?? []
        );
        
        // Garanties optionnelles pour la période sélectionnée
        $garantiesOptionnelles = ($garantiesOptionnellesAnnuelle / 12) * $periodeMois;

        // Accessoires de police (fixes, pas proportionnels)
        $accessoires = $this->calculerAccessoiresPolice(
            $donnees['accessoires'] ?? []
        );

        // Prime nette
        $primeNette = $primeRC + $garantiesOptionnelles + $accessoires;

        // Taxes TUCA (19%)
        $taxes = $primeNette * self::TAUX_TUCA;

        // Prime TTC
        $primeTTC = $primeNette + $taxes;

        return [
            'prime_rc' => $primeRC,
            'prime_rc_annuelle' => $primeRCAnnuelle,
            'garanties_optionnelles' => $garantiesOptionnelles,
            'garanties_optionnelles_annuelle' => $garantiesOptionnellesAnnuelle,
            'accessoires_police' => $accessoires,
            'prime_nette' => $primeNette,
            'taxes_tuca' => $taxes,
            'prime_ttc' => $primeTTC,
            'periode_police' => $periodeMois,
            'details' => [
                'categorie' => $donnees['categorie'],
                'caracteristiques' => $donnees['caracteristiques'],
                'garanties_choisies' => array_keys(array_filter($donnees['garanties'] ?? [])),
                'taux_taxe' => self::TAUX_TUCA * 100 . '%',
                'periode_mois' => $periodeMois
            ]
        ];
    }

    /**
     * Obtient la liste des catégories disponibles
     */
    public function getCategories(): array
    {
        return [
            'C1' => 'Véhicules de tourisme (voitures particulières)',
            'C2' => 'Véhicules de commerce (utilitaires)',
            'C3' => 'Transports publics de marchandises',
            'C5' => 'Véhicules motorisés 2 ou 3 roues'
        ];
    }

    /**
     * Obtient la liste des garanties disponibles
     */
    public function getGaranties(): array
    {
        return [
            'vol' => 'Vol',
            'incendie' => 'Incendie',
            'bris_glace' => 'Bris de glace',
            'defense_recours' => 'Défense et recours',
            'individuelle_conducteur' => 'Individuelle conducteur',
            'dommages_tous_accidents' => 'Dommages tous accidents (DTA)'
        ];
    }
}
