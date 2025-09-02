<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Compagnie;
use App\Models\Garantie;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un utilisateur existant ou en créer un
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Récupérer des véhicules existants ou en créer
        $vehicles = Vehicle::where('user_id', $user->id)->get();
        if ($vehicles->isEmpty()) {
            $vehicles = collect([
                Vehicle::create([
                    'user_id' => $user->id,
                    'brand' => 'Renault',
                    'model' => 'Clio',
                    'year' => 2020,
                    'plate_number' => 'DK 1234 AA',
                    'power_fiscal' => 5,
                    'category' => 'Citadine',
                    'fuel_type' => 'Essence',
                    'mileage' => 50000,
                    'color' => 'Blanc',
                ]),
                Vehicle::create([
                    'user_id' => $user->id,
                    'brand' => 'Peugeot',
                    'model' => '308',
                    'year' => 2021,
                    'plate_number' => 'DK 5678 BB',
                    'power_fiscal' => 6,
                    'category' => 'Berline',
                    'fuel_type' => 'Diesel',
                    'mileage' => 30000,
                    'color' => 'Gris',
                ])
            ]);
        }

        // Récupérer des compagnies existantes
        $compagnies = Compagnie::all();
        if ($compagnies->isEmpty()) {
            $compagnies = collect([
                Compagnie::create([
                    'nom' => 'AXA Assurance',
                    'description' => 'Leader français de l\'assurance',
                    'is_active' => true,
                ]),
                Compagnie::create([
                    'nom' => 'Allianz',
                    'description' => 'Compagnie internationale d\'assurance de référence',
                    'is_active' => true,
                ])
            ]);
        }

        // Récupérer des garanties existantes
        $garanties = Garantie::all();
        if ($garanties->isEmpty()) {
            $garanties = collect([
                Garantie::create([
                    'name' => 'Responsabilité Civile',
                    'display_name' => 'Responsabilité Civile',
                    'description' => 'Garantie obligatoire couvrant les dommages causés aux tiers',
                    'coefficient' => 1.0,
                    'is_required' => true,
                    'is_active' => true,
                ]),
                Garantie::create([
                    'name' => 'Vol et Incendie',
                    'display_name' => 'Vol et Incendie',
                    'description' => 'Protection contre le vol et l\'incendie du véhicule',
                    'coefficient' => 0.8,
                    'is_required' => false,
                    'is_active' => true,
                ])
            ]);
        }

        // Créer des contrats de test
        $contrats = [
            [
                'contract_number' => 'CTR-2024-001',
                'user_id' => $user->id,
                'vehicle_id' => $vehicles->first()->id,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'duration_months' => 12,
                'base_premium' => 1000.00,
                'total_premium' => 1200.00,
                'taxes' => 200.00,
                'status' => 'active',
                'garanties' => [$garanties->first()->id, $garanties->last()->id]
            ],
            [
                'contract_number' => 'CTR-2024-002',
                'user_id' => $user->id,
                'vehicle_id' => $vehicles->last()->id,
                'start_date' => '2024-02-01',
                'end_date' => '2025-01-31',
                'duration_months' => 12,
                'base_premium' => 1500.00,
                'total_premium' => 1800.00,
                'taxes' => 300.00,
                'status' => 'active',
                'garanties' => [$garanties->first()->id]
            ]
        ];

        foreach ($contrats as $contratData) {
            $garantiesIds = $contratData['garanties'];
            unset($contratData['garanties']);
            
            $contract = Contract::create($contratData);
            
            // Attacher les garanties
            foreach ($garantiesIds as $garantieId) {
                $garantie = $garanties->find($garantieId);
                if ($garantie) {
                    $contract->garanties()->attach($garantieId, [
                        'coefficient' => $garantie->coefficient,
                        'premium' => $contract->montant_annuel * $garantie->coefficient
                    ]);
                }
            }
        }

        $this->command->info('Contrats de test créés avec succès !');
    }
}
