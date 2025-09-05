<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestSouscriptionController extends Controller
{
    public function test(Request $request)
    {
        try {
            $data = $request->all();
            
            return response()->json([
                'success' => true,
                'message' => 'Test réussi !',
                'data_received' => $data,
                'timestamp' => now()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    public function testModels(Request $request)
    {
        try {
            // Test de création d'utilisateur
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
            
            // Test de création de véhicule
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
                'numero_chassis' => 'VF3XXXXXXXXXXXXXXX' . time(),
                'categorie' => 'voiture_particuliere',
                'proprietaire_nom' => $user->nom,
                'proprietaire_prenom' => $user->prenom,
                'proprietaire_adresse' => $user->adresse,
                'proprietaire_telephone' => $user->Telephone,
                'proprietaire_email' => $user->email,
            ]);
            
            // Test de création de contrat
            $contrat = \App\Models\Contrat::create([
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id,
                'compagnie_id' => 1,
                'numero_police' => 'POL' . time(),
                'numero_attestation' => 'ATT' . time(),
                'cle_securite' => 'KEY' . time(),
                'date_debut' => now(),
                'date_fin' => now()->addMonths(1),
                'periode_police' => 1,
                'garanties_selectionnees' => json_encode(['RC', 'Vol']),
                'prime_rc' => 3405,
                'garanties_optionnelles' => 200,
                'accessoires_police' => 2000,
                'prime_nette' => 5605,
                'taxes_tuca' => 1065,
                'prime_ttc' => 6670,
                'statut' => 'actif',
                'date_souscription' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tous les modèles fonctionnent !',
                'data' => [
                    'user_id' => $user->id,
                    'vehicule_id' => $vehicule->id,
                    'contrat_id' => $contrat->id,
                    'user_email' => $user->email,
                    'vehicule_immatriculation' => $vehicule->immatriculation,
                    'contrat_numero_police' => $contrat->numero_police
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}