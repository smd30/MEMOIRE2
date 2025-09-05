<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StepByStepController extends Controller
{
    public function test(Request $request)
    {
        $data = $request->all();
        $results = [];
        
        // Étape 1: Créer un utilisateur
        try {
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
            $results['user_created'] = 'OK - ID: ' . $user->id;
        } catch (\Exception $e) {
            $results['user_created'] = 'ERROR: ' . $e->getMessage();
            return response()->json(['results' => $results], 500);
        }
        
        // Étape 2: Créer un véhicule
        try {
            $vehicule = \App\Models\Vehicule::create([
                'user_id' => $user->id,
                'marque_vehicule' => $data['vehicule']['marque_vehicule'] ?? 'PEUGEOT',
                'modele' => $data['vehicule']['modele'] ?? '206',
                'immatriculation' => $data['vehicule']['immatriculation'] ?? 'TEST' . time(),
                'puissance_fiscale' => (int) ($data['vehicule']['puissance_fiscale'] ?? 6),
                'date_mise_en_circulation' => $data['vehicule']['date_mise_en_circulation'] ?? '2010-01-15',
                'valeur_vehicule' => (int) ($data['vehicule']['valeur_vehicule'] ?? 5000000),
                'energie' => $data['vehicule']['energie'] ?? 'essence',
                'places' => (int) ($data['vehicule']['places'] ?? 5),
                'numero_chassis' => $data['vehicule']['numero_chassis'] ?? 'VF3XXXXXXXXXXXXXXX',
                'categorie' => 'voiture_particuliere',
                'proprietaire_nom' => $user->nom,
                'proprietaire_prenom' => $user->prenom,
                'proprietaire_adresse' => $user->adresse,
                'proprietaire_telephone' => $user->Telephone,
                'proprietaire_email' => $user->email,
            ]);
            $results['vehicule_created'] = 'OK - ID: ' . $vehicule->id;
        } catch (\Exception $e) {
            $results['vehicule_created'] = 'ERROR: ' . $e->getMessage();
            return response()->json(['results' => $results], 500);
        }
        
        // Étape 3: Créer un contrat
        try {
            $contrat = \App\Models\Contrat::create([
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id,
                'compagnie_id' => (int) ($data['compagnie_id'] ?? 1),
                'numero_police' => 'POL' . time(),
                'numero_attestation' => 'ATT' . time(),
                'cle_securite' => 'KEY' . time(),
                'date_debut' => \Carbon\Carbon::parse($data['date_debut'] ?? now()),
                'date_fin' => \Carbon\Carbon::parse($data['date_debut'] ?? now())->addMonths((int) ($data['periode_police'] ?? 1)),
                'periode_police' => (int) ($data['periode_police'] ?? 1),
                'garanties_selectionnees' => json_encode($data['garanties_selectionnees'] ?? ['RC', 'Vol']),
                'prime_rc' => (float) ($data['devis_calcule']['prime_rc'] ?? 3405),
                'garanties_optionnelles' => (float) ($data['devis_calcule']['garanties_optionnelles'] ?? 200),
                'accessoires_police' => (float) ($data['devis_calcule']['accessoires_police'] ?? 2000),
                'prime_nette' => (float) ($data['devis_calcule']['prime_nette'] ?? 5605),
                'taxes_tuca' => (float) ($data['devis_calcule']['taxes_tuca'] ?? 1065),
                'prime_ttc' => (float) ($data['devis_calcule']['prime_ttc'] ?? 6670),
                'statut' => 'actif',
                'date_souscription' => now(),
            ]);
            $results['contrat_created'] = 'OK - ID: ' . $contrat->id;
        } catch (\Exception $e) {
            $results['contrat_created'] = 'ERROR: ' . $e->getMessage();
            return response()->json(['results' => $results], 500);
        }
        
        // Étape 4: Envoyer un email
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
            
            $results['email_sent'] = 'OK';
        } catch (\Exception $e) {
            $results['email_sent'] = 'ERROR: ' . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Toutes les étapes ont été exécutées avec succès',
            'results' => $results,
            'data' => [
                'contrat' => [
                    'id' => $contrat->id,
                    'numero_police' => $contrat->numero_police,
                    'numero_attestation' => $contrat->numero_attestation,
                    'date_debut' => $contrat->date_debut->format('d/m/Y'),
                    'date_fin' => $contrat->date_fin->format('d/m/Y'),
                    'prime_ttc' => number_format($contrat->prime_ttc, 0, ',', ' '),
                    'garanties' => json_decode($contrat->garanties_selectionnees, true)
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
                'email_sent' => $results['email_sent'] === 'OK',
                'message' => $results['email_sent'] === 'OK' ? 
                    'Votre contrat a été créé avec succès. L\'attestation a été envoyée par email.' : 
                    'Votre contrat a été créé avec succès. L\'attestation sera envoyée prochainement.'
            ]
        ]);
    }
}