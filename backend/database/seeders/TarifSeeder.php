<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Garantie;
use App\Models\TarifCategory;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer d'abord les garanties de base
        $this->createGaranties();

        // Essayer d'importer depuis le fichier Excel
        if (!$this->importFromExcel()) {
            // Si pas de fichier Excel, créer des tarifs d'exemple
            $this->createExampleTarifs();
        }
    }

    /**
     * Créer les garanties de base
     */
    private function createGaranties(): void
    {
        $garanties = [
            [
                'name' => 'vol',
                'display_name' => 'Vol et Vandalisme',
                'description' => 'Protection contre le vol et les actes de vandalisme',
                'coefficient' => 1.20,
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'name' => 'incendie',
                'display_name' => 'Incendie',
                'description' => 'Protection contre les dommages par incendie',
                'coefficient' => 0.80,
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'name' => 'bris',
                'display_name' => 'Bris de Glace',
                'description' => 'Protection contre les bris de glace',
                'coefficient' => 1.00,
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'name' => 'defense',
                'display_name' => 'Défense et Recours',
                'description' => 'Protection juridique et défense en cas de litige',
                'coefficient' => 1.10,
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'name' => 'dommages',
                'display_name' => 'Dommages Collision',
                'description' => 'Protection contre les dommages en cas de collision',
                'coefficient' => 1.50,
                'is_required' => false,
                'is_active' => true,
            ],
        ];

        foreach ($garanties as $garantie) {
            Garantie::updateOrCreate(
                ['name' => $garantie['name']],
                $garantie
            );
        }

        $this->command->info('Garanties créées avec succès');
    }

    /**
     * Importer les tarifs depuis un fichier Excel
     */
    private function importFromExcel(): bool
    {
        $excelPath = '/data/grille_tarifaire.xlsx';
        
        if (!file_exists($excelPath)) {
            $this->command->warn('Fichier Excel non trouvé: ' . $excelPath);
            return false;
        }

        try {
            $this->command->info('Import des tarifs depuis le fichier Excel...');
            
            // Vider la table des tarifs existants
            TarifCategory::truncate();

            // Importer depuis Excel
            Excel::import(new class implements \Maatwebsite\Excel\Concerns\ToArray {
                public function array(array $array)
                {
                    // Ignorer l'en-tête
                    array_shift($array);
                    
                    foreach ($array as $row) {
                        if (count($row) >= 6) {
                            TarifCategory::create([
                                'name' => $row[0] ?? 'Citadine',
                                'sub_category' => $row[1] ?? null,
                                'power_fiscal_min' => (int)($row[2] ?? 1),
                                'power_fiscal_max' => (int)($row[3] ?? 5),
                                'base_rate_monthly' => (float)($row[4] ?? 40.00),
                                'coefficient_vol' => (float)($row[5] ?? 1.20),
                                'coefficient_incendie' => (float)($row[6] ?? 0.80),
                                'coefficient_bris' => (float)($row[7] ?? 1.00),
                                'coefficient_defense' => (float)($row[8] ?? 1.10),
                                'conditions' => $row[9] ?? null,
                                'is_active' => true,
                            ]);
                        }
                    }
                }
            }, $excelPath);

            $this->command->info('Tarifs importés avec succès depuis Excel');
            return true;

        } catch (\Exception $e) {
            $this->command->error('Erreur lors de l\'import Excel: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Créer des tarifs d'exemple
     */
    private function createExampleTarifs(): void
    {
        $this->command->info('Création de tarifs d\'exemple...');

        // Vider la table des tarifs existants
        TarifCategory::truncate();

        $tarifs = [
            // Citadines
            ['Citadine', 'Compacte', 1, 4, 35.00, 1.20, 0.80, 1.00, 1.10, 'Véhicule < 5 ans'],
            ['Citadine', 'Compacte', 5, 8, 40.00, 1.20, 0.80, 1.00, 1.10, 'Véhicule < 5 ans'],
            ['Citadine', 'Familiale', 1, 6, 38.00, 1.20, 0.80, 1.00, 1.10, 'Véhicule < 5 ans'],
            ['Citadine', 'Familiale', 7, 10, 43.00, 1.20, 0.80, 1.00, 1.10, 'Véhicule < 5 ans'],
            
            // SUV
            ['SUV', 'Compact', 1, 6, 50.00, 1.25, 0.85, 1.05, 1.15, 'Véhicule < 5 ans'],
            ['SUV', 'Compact', 7, 12, 55.00, 1.25, 0.85, 1.05, 1.15, 'Véhicule < 5 ans'],
            ['SUV', 'Familial', 1, 8, 55.00, 1.25, 0.85, 1.05, 1.15, 'Véhicule < 5 ans'],
            ['SUV', 'Familial', 9, 15, 60.00, 1.25, 0.85, 1.05, 1.15, 'Véhicule < 5 ans'],
            
            // Berlines
            ['Berline', 'Compacte', 1, 6, 45.00, 1.22, 0.82, 1.02, 1.12, 'Véhicule < 5 ans'],
            ['Berline', 'Compacte', 7, 12, 50.00, 1.22, 0.82, 1.02, 1.12, 'Véhicule < 5 ans'],
            ['Berline', 'Familiale', 1, 8, 48.00, 1.22, 0.82, 1.02, 1.12, 'Véhicule < 5 ans'],
            ['Berline', 'Familiale', 9, 15, 53.00, 1.22, 0.82, 1.02, 1.12, 'Véhicule < 5 ans'],
            
            // Utilitaires
            ['Utilitaire', 'Petit', 1, 6, 45.00, 1.30, 0.90, 1.10, 1.20, 'Véhicule < 5 ans'],
            ['Utilitaire', 'Petit', 7, 12, 50.00, 1.30, 0.90, 1.10, 1.20, 'Véhicule < 5 ans'],
            ['Utilitaire', 'Grand', 1, 8, 50.00, 1.30, 0.90, 1.10, 1.20, 'Véhicule < 5 ans'],
            ['Utilitaire', 'Grand', 9, 20, 55.00, 1.30, 0.90, 1.10, 1.20, 'Véhicule < 5 ans'],
            
            // Motos
            ['Moto', '125cc', 1, 4, 25.00, 1.15, 0.75, 0.95, 1.05, 'Véhicule < 5 ans'],
            ['Moto', '125cc', 5, 8, 28.00, 1.15, 0.75, 0.95, 1.05, 'Véhicule < 5 ans'],
            ['Moto', '500cc', 1, 6, 30.00, 1.15, 0.75, 0.95, 1.05, 'Véhicule < 5 ans'],
            ['Moto', '500cc', 7, 12, 33.00, 1.15, 0.75, 0.95, 1.05, 'Véhicule < 5 ans'],
        ];

        foreach ($tarifs as $tarif) {
            TarifCategory::create([
                'name' => $tarif[0],
                'sub_category' => $tarif[1],
                'power_fiscal_min' => $tarif[2],
                'power_fiscal_max' => $tarif[3],
                'base_rate_monthly' => $tarif[4],
                'coefficient_vol' => $tarif[5],
                'coefficient_incendie' => $tarif[6],
                'coefficient_bris' => $tarif[7],
                'coefficient_defense' => $tarif[8],
                'conditions' => $tarif[9],
                'is_active' => true,
            ]);
        }

        $this->command->info('Tarifs d\'exemple créés avec succès');
    }

    /**
     * Vider la table des tarifs
     */
    public function clear(): void
    {
        TarifCategory::truncate();
        $this->command->info('Table des tarifs vidée');
    }
}
