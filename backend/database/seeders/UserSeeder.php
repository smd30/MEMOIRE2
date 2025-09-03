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
                'name' => 'Mamadou Ndiaye',
                'email' => 'ndiaye@gmail.com',
                'phone' => '+221 77 123 45 67',
                'password' => 'password',
                'role' => 'client',
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
                'name' => 'Client Test',
                'email' => 'client@test.com',
                'phone' => '+33123456789',
                'password' => 'password',
                'role' => 'client',
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
                'name' => 'Gestionnaire Test',
                'email' => 'gestionnaire@test.com',
                'phone' => '+33123456790',
                'password' => 'password',
                'role' => 'gestionnaire',
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
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'phone' => '+33123456791',
                'password' => 'password',
                'role' => 'admin',
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
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => Hash::make($userData['password']),
                'is_active' => true,
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

            // Assigner le rôle
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

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
