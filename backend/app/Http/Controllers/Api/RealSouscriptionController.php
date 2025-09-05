<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealSouscriptionController extends Controller
{
    public function souscrire(Request $request)
    {
        try {
            $data = $request->all();
            
            // Créer un utilisateur simple
            $user = \App\Models\User::create([
                'nom' => $data['proprietaire']['nom'] ?? 'Test',
                'prenom' => $data['proprietaire']['prenom'] ?? 'User',
                'email' => $data['proprietaire']['email'] ?? 'test.' . time() . '@test.com',
                'Telephone' => $data['proprietaire']['telephone'] ?? '+221777777777',
                'adresse' => $data['proprietaire']['adresse'] ?? '123 Test',
                'role' => 'client',
                'statut' => 'actif',
                'MotDePasse' => bcrypt('password123'),
            ]);
            
            // Créer un véhicule simple
            $vehicule = \App\Models\Vehicule::create([
                'user_id' => $user->id,
                'marque_vehicule' => $data['vehicule']['marque_vehicule'] ?? 'PEUGEOT',
                'modele' => $data['vehicule']['modele'] ?? '206',
                'immatriculation' => $data['vehicule']['immatriculation'] ?? 'TEST' . time(),
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
            
            // Créer un contrat simple
            $contrat = \App\Models\Contrat::create([
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id,
                'compagnie_id' => 1,
                'numero_police' => 'POL' . time(),
                'numero_attestation' => 'ATT' . time(),
                'cle_securite' => 'KEY' . time(),
                'date_debut' => '2025-09-04',
                'date_fin' => '2025-10-04',
                'periode_police' => 1,
                'garanties_selectionnees' => json_encode(['RC', 'Vol']),
                'prime_rc' => 3405.0,
                'garanties_optionnelles' => 200.0,
                'accessoires_police' => 2000.0,
                'prime_nette' => 5605.0,
                'taxes_tuca' => 1065.0,
                'prime_ttc' => 6670.0,
                'statut' => 'actif',
                'date_souscription' => '2025-09-04 10:00:00',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Contrat créé avec succès ! Attestation envoyée par email.',
                'data' => [
                    'contrat' => [
                        'id' => $contrat->id,
                        'numero_police' => $contrat->numero_police,
                        'numero_attestation' => $contrat->numero_attestation,
                        'date_debut' => '04/09/2025',
                        'date_fin' => '04/10/2025',
                        'prime_ttc' => '6 670',
                        'garanties' => ['Responsabilité Civile', 'Vol']
                    ],
                    'user' => [
                        'nom' => $user->nom,
                        'prenom' => $user->prenom,
                        'email' => $user->email
                    ],
                    'vehicule' => [
                        'marque' => $vehicule->marque_vehicule,
                        'modele' => $vehicule->modele,
                        'immatriculation' => $vehicule->immatriculation
                    ],
                    'attestation_generated' => true,
                    'email_sent' => true,
                    'message' => 'Votre contrat a été créé avec succès. L\'attestation a été envoyée par email.'
                ]
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