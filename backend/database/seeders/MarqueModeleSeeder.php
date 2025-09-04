<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;
use App\Models\Modele;

class MarqueModeleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marques = [
            [
                'nom' => 'Toyota',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur automobile japonais leader mondial',
                'modeles' => [
                    ['nom' => 'Corolla', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Camry', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Hilux', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Land Cruiser', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Yaris', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Avensis', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Hiace', 'categorie_vehicule' => 'transport_commun'],
                    ['nom' => 'Coaster', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'Nissan',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur automobile japonais',
                'modeles' => [
                    ['nom' => 'Sunny', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Almera', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Primera', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Patrol', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Navara', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Urvan', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'Honda',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur automobile japonais',
                'modeles' => [
                    ['nom' => 'Civic', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Accord', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'CR-V', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'City', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Jazz', 'categorie_vehicule' => 'vehicules_particuliers']
                ]
            ],
            [
                'nom' => 'Suzuki',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur automobile japonais',
                'modeles' => [
                    ['nom' => 'Swift', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Vitara', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Jimny', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Alto', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Carry', 'categorie_vehicule' => 'transport_marchandises']
                ]
            ],
            [
                'nom' => 'Mitsubishi',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur automobile japonais',
                'modeles' => [
                    ['nom' => 'Lancer', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Pajero', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Outlander', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'L200', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Canter', 'categorie_vehicule' => 'transport_marchandises']
                ]
            ],
            [
                'nom' => 'Peugeot',
                'pays_origine' => 'France',
                'description' => 'Constructeur automobile français',
                'modeles' => [
                    ['nom' => '206', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => '207', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => '307', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => '308', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => '406', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => '407', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Partner', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Boxer', 'categorie_vehicule' => 'transport_marchandises']
                ]
            ],
            [
                'nom' => 'Renault',
                'pays_origine' => 'France',
                'description' => 'Constructeur automobile français',
                'modeles' => [
                    ['nom' => 'Clio', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Megane', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Laguna', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Kangoo', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Master', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Trafic', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'Citroën',
                'pays_origine' => 'France',
                'description' => 'Constructeur automobile français',
                'modeles' => [
                    ['nom' => 'C3', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'C4', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'C5', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Berlingo', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Jumper', 'categorie_vehicule' => 'transport_marchandises']
                ]
            ],
            [
                'nom' => 'Volkswagen',
                'pays_origine' => 'Allemagne',
                'description' => 'Constructeur automobile allemand',
                'modeles' => [
                    ['nom' => 'Golf', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Passat', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Polo', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Jetta', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Tiguan', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Transporter', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'BMW',
                'pays_origine' => 'Allemagne',
                'description' => 'Constructeur automobile allemand de luxe',
                'modeles' => [
                    ['nom' => 'Série 3', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Série 5', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Série 7', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'X3', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'X5', 'categorie_vehicule' => 'vehicules_particuliers']
                ]
            ],
            [
                'nom' => 'Mercedes-Benz',
                'pays_origine' => 'Allemagne',
                'description' => 'Constructeur automobile allemand de luxe',
                'modeles' => [
                    ['nom' => 'Classe A', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Classe C', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Classe E', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Classe S', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Sprinter', 'categorie_vehicule' => 'transport_commun'],
                    ['nom' => 'Vito', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'Audi',
                'pays_origine' => 'Allemagne',
                'description' => 'Constructeur automobile allemand de luxe',
                'modeles' => [
                    ['nom' => 'A3', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'A4', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'A6', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Q3', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Q5', 'categorie_vehicule' => 'vehicules_particuliers']
                ]
            ],
            [
                'nom' => 'Hyundai',
                'pays_origine' => 'Corée du Sud',
                'description' => 'Constructeur automobile coréen',
                'modeles' => [
                    ['nom' => 'Accent', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Elantra', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Sonata', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Tucson', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Santa Fe', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'H100', 'categorie_vehicule' => 'transport_marchandises']
                ]
            ],
            [
                'nom' => 'Kia',
                'pays_origine' => 'Corée du Sud',
                'description' => 'Constructeur automobile coréen',
                'modeles' => [
                    ['nom' => 'Rio', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Cerato', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Optima', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Sportage', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Sorento', 'categorie_vehicule' => 'vehicules_particuliers']
                ]
            ],
            [
                'nom' => 'Ford',
                'pays_origine' => 'États-Unis',
                'description' => 'Constructeur automobile américain',
                'modeles' => [
                    ['nom' => 'Focus', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Mondeo', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Fiesta', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Ranger', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Transit', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'Opel',
                'pays_origine' => 'Allemagne',
                'description' => 'Constructeur automobile allemand',
                'modeles' => [
                    ['nom' => 'Corsa', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Astra', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Vectra', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Combo', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Vivaro', 'categorie_vehicule' => 'transport_commun']
                ]
            ],
            [
                'nom' => 'Fiat',
                'pays_origine' => 'Italie',
                'description' => 'Constructeur automobile italien',
                'modeles' => [
                    ['nom' => 'Punto', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Palio', 'categorie_vehicule' => 'vehicules_particuliers'],
                    ['nom' => 'Ducato', 'categorie_vehicule' => 'transport_marchandises'],
                    ['nom' => 'Doblo', 'categorie_vehicule' => 'transport_marchandises']
                ]
            ],
            [
                'nom' => 'Yamaha',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur de motos japonais',
                'modeles' => [
                    ['nom' => 'YBR 125', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'YBR 250', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'FZ 150', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'FZ 250', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'MT-07', 'categorie_vehicule' => 'deux_trois_roues']
                ]
            ],
            [
                'nom' => 'Honda',
                'pays_origine' => 'Japon',
                'description' => 'Constructeur de motos japonais',
                'modeles' => [
                    ['nom' => 'CG 125', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'CG 150', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'CBR 150R', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'CBR 250R', 'categorie_vehicule' => 'deux_trois_roues'],
                    ['nom' => 'CB 500F', 'categorie_vehicule' => 'deux_trois_roues']
                ]
            ]
        ];

        foreach ($marques as $marqueData) {
            $modeles = $marqueData['modeles'];
            unset($marqueData['modeles']);

            // Créer la marque
            $marque = Marque::create($marqueData);

            // Créer les modèles pour cette marque
            foreach ($modeles as $modeleData) {
                $modeleData['marque_id'] = $marque->id;
                Modele::create($modeleData);
            }

            $this->command->info("Marque {$marque->nom} créée avec " . count($modeles) . " modèles");
        }

        $this->command->info('Marques et modèles créés avec succès');
    }
}
