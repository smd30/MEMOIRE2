<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestVehiculeController extends Controller
{
    public function test(Request $request)
    {
        try {
            $data = $request->all();
            
            // Créer un utilisateur d'abord
            $user = \App\Models\User::create([
                'nom' => 'Test',
                'prenom' => 'User',
                'email' => 'test.' . time() . '@test.com',
                'Telephone' => '+221777777777',
                'adresse' => '123 Test',
                'role' => 'client',
                'statut' => 'actif',
                'MotDePasse' => bcrypt('password123'),
            ]);
            
            // Créer un véhicule
            $vehicule = \App\Models\Vehicule::create([
                'user_id' => $user->id,
                'marque_vehicule' => 'PEUGEOT',
                'modele' => '206',
                'immatriculation' => 'TEST' . time(),
                'puissance_fiscale' => 6,
                'date_mise_en_circulation' => '2010-01-15',
                'valeur_vehicule' => 5000000,
                'energie' => 'essence',
                'places' => 5,
                'numero_chassis' => 'VF3XXXXXXXXXXXXXXX',
                'categorie' => 'voiture_particuliere',
                'proprietaire_nom' => $user->nom,
                'proprietaire_prenom' => $user->prenom,
                'proprietaire_adresse' => $user->adresse,
                'proprietaire_telephone' => $user->Telephone,
                'proprietaire_email' => $user->email,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Véhicule créé avec succès',
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
