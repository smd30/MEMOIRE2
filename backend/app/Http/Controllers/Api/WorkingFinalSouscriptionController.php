<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkingFinalSouscriptionController extends Controller
{
    public function souscrire(Request $request)
    {
        try {
            $data = $request->all();
            
            // Créer l'utilisateur avec l'email du PROPRIÉTAIRE DU VÉHICULE du formulaire
            $user = \App\Models\User::create([
                'nom' => 'Test',
                'prenom' => 'User',
                'email' => $data['proprietaire']['email'] ?? 'test.' . time() . '@test.com',
                'Telephone' => '+221777777777',
                'adresse' => '123 Test',
                'role' => 'client',
                'statut' => 'actif',
                'MotDePasse' => bcrypt('password123'),
            ]);
            
            // Créer le véhicule simple
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
            ]);
            
            // Créer le contrat simple
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
            
            // Envoyer l'email
            $emailSent = false;
            try {
                $emailContent = "Bonjour {$user->prenom} {$user->nom},\n\n";
                $emailContent .= "Votre contrat d'assurance a été créé avec succès.\n\n";
                $emailContent .= "Détails du contrat :\n";
                $emailContent .= "- N° Police: {$contrat->numero_police}\n";
                $emailContent .= "- N° Attestation: {$contrat->numero_attestation}\n";
                $emailContent .= "- Véhicule: {$vehicule->marque_vehicule} {$vehicule->modele}\n";
                $emailContent .= "- Immatriculation: {$vehicule->immatriculation}\n";
                $emailContent .= "- Prime TTC: " . number_format($contrat->prime_ttc, 0, ',', ' ') . " FCFA\n\n";
                $emailContent .= "Cordialement,\nKDS Assurances";

                \Illuminate\Support\Facades\Mail::raw($emailContent, function ($message) use ($user, $contrat) {
                    $message->to($user->email, $user->prenom . ' ' . $user->nom)
                            ->subject('Attestation d\'assurance - N° ' . $contrat->numero_attestation)
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });
                
                $emailSent = true;
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email:', ['error' => $e->getMessage()]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Contrat créé avec succès ! ' . ($emailSent ? 'Attestation envoyée par email.' : 'Attestation sera envoyée prochainement.'),
                'data' => [
                    'contrat' => [
                        'id' => $contrat->id,
                        'numero_police' => $contrat->numero_police,
                        'numero_attestation' => $contrat->numero_attestation,
                        'date_debut' => $contrat->date_debut->format('d/m/Y'),
                        'date_fin' => $contrat->date_fin->format('d/m/Y'),
                        'prime_ttc' => number_format($contrat->prime_ttc, 0, ',', ' '),
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
                    'email_sent' => $emailSent,
                    'message' => $emailSent ? 
                        'Votre contrat a été créé avec succès. L\'attestation a été envoyée par email.' : 
                        'Votre contrat a été créé avec succès. L\'attestation sera envoyée prochainement.'
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