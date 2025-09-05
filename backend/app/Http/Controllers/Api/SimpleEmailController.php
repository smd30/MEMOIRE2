<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleEmailController extends Controller
{
    public function test(Request $request)
    {
        try {
            $data = $request->all();
            
            DB::beginTransaction();
            
            // Test 1: Créer un utilisateur
            try {
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
                \Log::info('Utilisateur créé:', ['id' => $user->id]);
            } catch (\Exception $e) {
                \Log::error('Erreur création utilisateur:', ['error' => $e->getMessage()]);
                DB::rollback();
                return response()->json(['error' => 'User creation error: ' . $e->getMessage()], 500);
            }
            
            // Test 2: Créer un véhicule
            try {
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
                \Log::info('Véhicule créé:', ['id' => $vehicule->id]);
            } catch (\Exception $e) {
                \Log::error('Erreur création véhicule:', ['error' => $e->getMessage()]);
                DB::rollback();
                return response()->json(['error' => 'Vehicule creation error: ' . $e->getMessage()], 500);
            }
            
            // Test 3: Créer un contrat
            try {
                $contrat = \App\Models\Contrat::create([
                    'user_id' => $user->id,
                    'vehicule_id' => $vehicule->id,
                    'compagnie_id' => 1,
                    'numero_police' => 'POL' . time(),
                    'numero_attestation' => 'ATT' . time(),
                    'cle_securite' => 'KEY' . time(),
                    'date_debut' => now(),
                    'date_fin' => now()->addMonth(),
                    'periode_police' => 1,
                    'garanties_selectionnees' => json_encode(['RC', 'Vol']),
                    'prime_rc' => 3405.0,
                    'garanties_optionnelles' => 200.0,
                    'accessoires_police' => 2000.0,
                    'prime_nette' => 5605.0,
                    'taxes_tuca' => 1065.0,
                    'prime_ttc' => 6670.0,
                    'statut' => 'actif',
                    'date_souscription' => now(),
                ]);
                \Log::info('Contrat créé:', ['id' => $contrat->id]);
            } catch (\Exception $e) {
                \Log::error('Erreur création contrat:', ['error' => $e->getMessage()]);
                DB::rollback();
                return response()->json(['error' => 'Contrat creation error: ' . $e->getMessage()], 500);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Création réussie',
                'data' => [
                    'user_id' => $user->id,
                    'vehicule_id' => $vehicule->id,
                    'contrat_id' => $contrat->id,
                    'numero_police' => $contrat->numero_police
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erreur générale:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}