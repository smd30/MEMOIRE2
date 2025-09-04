<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\ClientProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles de base
        Role::createDefaultRoles();

        // Créer les utilisateurs de test
        $this->createTestUsers();
    }

    /**
     * Créer les utilisateurs de test
     */
    private function createTestUsers(): void
    {
        $users = [
            [
                'nom' => 'Ndiaye',
                'prenom' => 'Mamadou',
                'email' => 'ndiaye@gmail.com',
                'Telephone' => '+221 77 123 45 67',
                'MotDePasse' => Hash::make('password'),
                'role' => 'client',
                'statut' => 'actif',
                'adresse' => '123 Rue de la Paix, Dakar',
                'profile' => [
                    'address' => '123 Rue de la Paix',
                    'city' => 'Dakar',
                    'postal_code' => '10000',
                    'country' => 'Sénégal',
                    'birth_date' => '1985-06-15',
                    'driving_license_number' => 'SN123456789',
                    'driving_license_date' => '2003-06-15',
                    'driving_experience_years' => 20,
                    'has_garage' => true,
                ],
            ],
            [
                'nom' => 'Test',
                'prenom' => 'Client',
                'email' => 'client@test.com',
                'Telephone' => '+33123456789',
                'MotDePasse' => Hash::make('password'),
                'role' => 'client',
                'statut' => 'actif',
                'adresse' => '123 Rue de la Paix, Paris',
                'profile' => [
                    'address' => '123 Rue de la Paix',
                    'city' => 'Paris',
                    'postal_code' => '75001',
                    'country' => 'France',
                    'birth_date' => '1985-06-15',
                    'driving_license_number' => '123456789',
                    'driving_license_date' => '2003-06-15',
                    'driving_experience_years' => 20,
                    'has_garage' => true,
                ],
            ],
            [
                'nom' => 'Test',
                'prenom' => 'Gestionnaire',
                'email' => 'gestionnaire@test.com',
                'Telephone' => '+33123456790',
                'MotDePasse' => Hash::make('password'),
                'role' => 'gestionnaire',
                'statut' => 'actif',
                'adresse' => '456 Avenue des Champs, Lyon',
                'profile' => [
                    'address' => '456 Avenue des Champs',
                    'city' => 'Lyon',
                    'postal_code' => '69001',
                    'country' => 'France',
                    'birth_date' => '1978-03-22',
                    'driving_license_number' => '987654321',
                    'driving_license_date' => '1996-03-22',
                    'driving_experience_years' => 27,
                    'has_garage' => false,
                ],
            ],
            [
                'nom' => 'Test',
                'prenom' => 'Admin',
                'email' => 'admin@test.com',
                'Telephone' => '+33123456791',
                'MotDePasse' => Hash::make('password'),
                'role' => 'admin',
                'statut' => 'actif',
                'adresse' => '789 Boulevard Central, Marseille',
                'profile' => [
                    'address' => '789 Boulevard Central',
                    'city' => 'Marseille',
                    'postal_code' => '13001',
                    'country' => 'France',
                    'birth_date' => '1970-11-08',
                    'driving_license_number' => '555666777',
                    'driving_license_date' => '1988-11-08',
                    'driving_experience_years' => 35,
                    'has_garage' => true,
                ],
            ],
        ];

        foreach ($users as $userData) {
            $profile = $userData['profile'];
            unset($userData['profile']);

            // Créer l'utilisateur
            $user = User::create([
                'nom' => $userData['nom'],
                'prenom' => $userData['prenom'],
                'email' => $userData['email'],
                'Telephone' => $userData['Telephone'],
                'MotDePasse' => $userData['MotDePasse'],
                'role' => $userData['role'],
                'statut' => $userData['statut'],
                'adresse' => $userData['adresse'],
            ]);

            // Créer le profil client
            ClientProfile::create([
                'user_id' => $user->id,
                'address' => $profile['address'],
                'city' => $profile['city'],
                'postal_code' => $profile['postal_code'],
                'country' => $profile['country'],
                'birth_date' => $profile['birth_date'],
                'driving_license_number' => $profile['driving_license_number'],
                'driving_license_date' => $profile['driving_license_date'],
                'driving_experience_years' => $profile['driving_experience_years'],
                'has_garage' => $profile['has_garage'],
            ]);

            $this->command->info("Utilisateur {$user->email} créé avec le rôle {$userData['role']}");
        }

        $this->command->info('Utilisateurs de test créés avec succès');
    }

    /**
     * Vider les utilisateurs de test
     */
    public function clear(): void
    {
        User::whereIn('email', [
            'client@test.com',
            'gestionnaire@test.com',
            'admin@test.com'
        ])->delete();

        $this->command->info('Utilisateurs de test supprimés');
    }
}
