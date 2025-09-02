<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Création des rôles de base...');

        Role::createDefaultRoles();

        $this->command->info('Rôles créés avec succès');
    }
}
