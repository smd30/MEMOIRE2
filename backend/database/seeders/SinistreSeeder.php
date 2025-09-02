<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sinistre;
use App\Models\User;
use App\Models\Contract;

class SinistreSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer ou créer un utilisateur de test
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'phone' => '0123456789',
                'address' => '123 Test Street',
                'city' => 'Test City',
                'postal_code' => '12345',
                'date_of_birth' => '1990-01-01',
                'gender' => 'M',
                'nationality' => 'Française',
                'id_number' => '123456789',
                'id_type' => 'cni',
                'profession' => 'Employé',
                'annual_income' => 50000,
            ]);
        }

        // Récupérer les contrats de l'utilisateur
        $contracts = Contract::where('user_id', $user->id)->get();

        if ($contracts->isEmpty()) {
            $this->command->info('Aucun contrat trouvé pour créer des sinistres de test');
            return;
        }

        // Créer des sinistres de test
        $sinistres = [
            [
                'user_id' => $user->id,
                'contract_id' => $contracts->first()->id,
                'incident_date' => '2024-01-15',
                'location' => 'Rue de la Paix, Paris',
                'description' => 'Collision avec un autre véhicule à un carrefour. Dommages à l\'avant droit du véhicule.',
                'type' => 'collision',
                'estimated_damage' => 2500.00,
                'status' => 'nouveau',
            ],
            [
                'user_id' => $user->id,
                'contract_id' => $contracts->first()->id,
                'incident_date' => '2024-02-20',
                'location' => 'Parking Centre Commercial, Lyon',
                'description' => 'Vol du véhicule dans un parking. Le véhicule a été retrouvé 3 jours plus tard avec des dégâts mineurs.',
                'type' => 'vol',
                'estimated_damage' => 800.00,
                'status' => 'valide',
                'manager_notes' => 'Véhicule retrouvé en bon état. Indemnisation accordée pour les dégâts mineurs.',
            ],
            [
                'user_id' => $user->id,
                'contract_id' => $contracts->last()->id,
                'incident_date' => '2024-03-10',
                'location' => 'Autoroute A6, près de Beaune',
                'description' => 'Dégâts causés par la grêle lors d\'un orage violent. Carrosserie endommagée.',
                'type' => 'bris',
                'estimated_damage' => 1800.00,
                'status' => 'en_cours',
                'manager_notes' => 'Expertise en cours. Photos reçues, évaluation en cours.',
            ],
        ];

        foreach ($sinistres as $sinistreData) {
            Sinistre::create($sinistreData);
        }

        $this->command->info('Sinistres de test créés avec succès !');
    }
}
