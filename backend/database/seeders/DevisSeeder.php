<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compagnie;
use App\Models\Garantie;

class DevisSeeder extends Seeder
{
    public function run()
    {
        // Créer les compagnies fictives avec les bonnes colonnes
        $compagnies = [
            [
                'nom' => 'AssurAuto Sénégal',
                'description' => 'Spécialiste de l\'assurance automobile au Sénégal',
                'adresse' => '123 Avenue Léopold Sédar Senghor, Dakar',
                'email' => 'contact@assurauto-senegal.com',
                'telephone' => '+221 33 123 45 67',
                'site_web' => 'https://www.assurauto-senegal.com',
                'logo_url' => 'https://api.assurauto-senegal.com/logo.png',
                'is_active' => true,
                'coefficient_tarif' => 1.00,
                'commission_rate' => 12.50,
                'api_endpoint' => 'https://api.assurauto-senegal.com',
                'api_key' => 'assurauto_key_2024'
            ],
            [
                'nom' => 'SécuriVie Assurance',
                'description' => 'Assurance automobile innovante et personnalisée',
                'adresse' => '456 Boulevard de la République, Dakar',
                'email' => 'info@securivie-assurance.com',
                'telephone' => '+221 33 987 65 43',
                'site_web' => 'https://www.securivie-assurance.com',
                'logo_url' => 'https://api.securivie-assurance.com/logo.png',
                'is_active' => true,
                'coefficient_tarif' => 0.95,
                'commission_rate' => 15.00,
                'api_endpoint' => 'https://api.securivie-assurance.com',
                'api_key' => 'securivie_key_2024'
            ]
        ];

        foreach ($compagnies as $compagnieData) {
            // Vérifier si la compagnie existe déjà
            $compagnie = Compagnie::where('nom', $compagnieData['nom'])->first();
            
            if (!$compagnie) {
                $compagnie = Compagnie::create($compagnieData);
            }

            // Créer les garanties pour chaque compagnie
            if ($compagnie->nom === 'AssurAuto Sénégal') {
                $garanties = [
                    [
                        'nom' => 'Responsabilité Civile',
                        'name' => 'rc',
                        'display_name' => 'Responsabilité Civile',
                        'description' => 'Garantie obligatoire couvrant les dommages causés à autrui',
                        'obligatoire' => true,
                        'tarification_type' => 'fixe',
                        'tarification_config' => ['montant' => 25000]
                    ],
                    [
                        'nom' => 'Vol',
                        'name' => 'vol',
                        'display_name' => 'Vol',
                        'description' => 'Protection contre le vol du véhicule',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 2.0]
                    ],
                    [
                        'nom' => 'Incendie',
                        'name' => 'incendie',
                        'display_name' => 'Incendie',
                        'description' => 'Protection contre les dommages causés par le feu',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 1.0]
                    ],
                    [
                        'nom' => 'Bris de glace',
                        'name' => 'bris_glace',
                        'display_name' => 'Bris de glace',
                        'description' => 'Remboursement des vitres, pare-brise et rétroviseurs endommagés',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 0.5]
                    ],
                    [
                        'nom' => 'Dommages collision',
                        'name' => 'dommages_collision',
                        'display_name' => 'Dommages collision',
                        'description' => 'Couverture pour les dommages en cas de collision',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 3.0]
                    ],
                    [
                        'nom' => 'Défense et recours',
                        'name' => 'defense_recours',
                        'display_name' => 'Défense et recours',
                        'description' => 'Prise en charge des frais juridiques en cas de litige',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 0.3]
                    ],
                    [
                        'nom' => 'Assistance 0km',
                        'name' => 'assistance_0km',
                        'display_name' => 'Assistance 0km',
                        'description' => 'Assistance dépannage et remorquage',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 0.2]
                    ]
                ];
            } else {
                $garanties = [
                    [
                        'nom' => 'Responsabilité Civile',
                        'name' => 'rc',
                        'display_name' => 'Responsabilité Civile',
                        'description' => 'Garantie obligatoire couvrant les dommages causés à autrui',
                        'obligatoire' => true,
                        'tarification_type' => 'fixe',
                        'tarification_config' => ['montant' => 25000]
                    ],
                    [
                        'nom' => 'Vol',
                        'name' => 'vol',
                        'display_name' => 'Vol',
                        'description' => 'Protection contre le vol du véhicule',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 2.0]
                    ],
                    [
                        'nom' => 'Incendie',
                        'name' => 'incendie',
                        'display_name' => 'Incendie',
                        'description' => 'Protection contre les dommages causés par le feu',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 1.0]
                    ],
                    [
                        'nom' => 'Bris de glace',
                        'name' => 'bris_glace',
                        'display_name' => 'Bris de glace',
                        'description' => 'Remboursement des vitres, pare-brise et rétroviseurs endommagés',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 0.5]
                    ],
                    [
                        'nom' => 'Dommages collision',
                        'name' => 'dommages_collision',
                        'display_name' => 'Dommages collision',
                        'description' => 'Couverture pour les dommages en cas de collision',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 3.0]
                    ],
                    [
                        'nom' => 'Défense et recours',
                        'name' => 'defense_recours',
                        'display_name' => 'Défense et recours',
                        'description' => 'Prise en charge des frais juridiques en cas de litige',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 0.3]
                    ],
                    [
                        'nom' => 'Assistance 0km',
                        'name' => 'assistance_0km',
                        'display_name' => 'Assistance 0km',
                        'description' => 'Assistance dépannage et remorquage',
                        'obligatoire' => false,
                        'tarification_type' => 'pourcentage',
                        'tarification_config' => ['taux' => 0.2]
                    ]
                ];
            }

            foreach ($garanties as $garantieData) {
                // Vérifier si la garantie existe déjà pour cette compagnie
                $existingGarantie = Garantie::where('name', $garantieData['name'])
                    ->where('compagnie_id', $compagnie->id)
                    ->first();
                
                if (!$existingGarantie) {
                    $garantieData['compagnie_id'] = $compagnie->id;
                    $garantieData['statut'] = 'active';
                    Garantie::create($garantieData);
                }
            }
        }
    }
}
