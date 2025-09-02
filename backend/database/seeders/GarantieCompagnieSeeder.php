<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Garantie;
use App\Models\Compagnie;

class GarantieCompagnieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les garanties
        $garanties = [
            [
                'name' => 'Responsabilité Civile',
                'display_name' => 'Responsabilité Civile',
                'description' => 'Garantie obligatoire couvrant les dommages causés aux tiers',
                'coefficient' => 1.0,
                'is_required' => true,
                'is_active' => true
            ],
            [
                'name' => 'Vol et Incendie',
                'display_name' => 'Vol et Incendie',
                'description' => 'Protection contre le vol et l\'incendie du véhicule',
                'coefficient' => 0.8,
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Bris de Glace',
                'display_name' => 'Bris de Glace',
                'description' => 'Remplacement des vitres et pare-brise en cas de bris',
                'coefficient' => 0.3,
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Défense et Recours',
                'display_name' => 'Défense et Recours',
                'description' => 'Assistance juridique et défense en cas de litige',
                'coefficient' => 0.5,
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Assistance 0km',
                'display_name' => 'Assistance 0km',
                'description' => 'Assistance dépannage et remorquage 24h/24',
                'coefficient' => 0.4,
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Personnes Transportées',
                'display_name' => 'Personnes Transportées',
                'description' => 'Protection des passagers en cas d\'accident',
                'coefficient' => 0.6,
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Tous Risques',
                'display_name' => 'Tous Risques',
                'description' => 'Protection complète incluant tous les dommages',
                'coefficient' => 2.0,
                'is_required' => false,
                'is_active' => true
            ]
        ];

        foreach ($garanties as $garantie) {
            Garantie::updateOrCreate(
                ['name' => $garantie['name']],
                $garantie
            );
        }

        // Créer les compagnies
        $compagnies = [
            [
                'nom' => 'AXA Assurance',
                'description' => 'Leader français de l\'assurance avec plus de 50 ans d\'expérience',
                'adresse' => '25 Avenue Matignon, 75008 Paris',
                'telephone' => '01 40 75 60 00',
                'email' => 'contact@axa.fr',
                'site_web' => 'https://www.axa.fr',
                'is_active' => true,
                'commission_rate' => 15.00
            ],
            [
                'nom' => 'Allianz',
                'description' => 'Compagnie internationale d\'assurance de référence',
                'adresse' => '1 Cours Michelet, 92800 Puteaux',
                'telephone' => '01 42 91 40 00',
                'email' => 'contact@allianz.fr',
                'site_web' => 'https://www.allianz.fr',
                'is_active' => true,
                'commission_rate' => 12.00
            ],
            [
                'nom' => 'Groupama',
                'description' => 'Mutuelle d\'assurance française spécialisée dans l\'assurance automobile',
                'adresse' => '8-10 rue d\'Astorg, 75008 Paris',
                'telephone' => '01 44 56 78 90',
                'email' => 'contact@groupama.fr',
                'site_web' => 'https://www.groupama.fr',
                'is_active' => true,
                'commission_rate' => 18.00
            ],
            [
                'nom' => 'MAIF',
                'description' => 'Mutuelle d\'assurance des instituteurs de France',
                'adresse' => '200 Avenue Salvador Allende, 79000 Niort',
                'telephone' => '05 49 73 73 73',
                'email' => 'contact@maif.fr',
                'site_web' => 'https://www.maif.fr',
                'is_active' => true,
                'commission_rate' => 10.00
            ],
            [
                'nom' => 'Direct Assurance',
                'description' => 'Assurance en ligne avec des tarifs compétitifs',
                'adresse' => 'Tour Allianz One, 1 Cours Michelet, 92800 Puteaux',
                'telephone' => '01 70 99 41 41',
                'email' => 'contact@direct-assurance.fr',
                'site_web' => 'https://www.direct-assurance.fr',
                'is_active' => true,
                'commission_rate' => 20.00
            ]
        ];

        foreach ($compagnies as $compagnie) {
            Compagnie::updateOrCreate(
                ['nom' => $compagnie['nom']],
                $compagnie
            );
        }

        $this->command->info('Garanties et compagnies créées avec succès !');
    }
}
