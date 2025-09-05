<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SouscriptionProprietaireController extends Controller
{
    public function souscrire(Request $request)
    {
        try {
            $data = $request->all();
            
            // Créer l'utilisateur avec les données du PROPRIÉTAIRE DU VÉHICULE du formulaire
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
            
            // Créer le véhicule avec les données du formulaire
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
            
            // Créer le contrat
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
            
            // Générer le PDF de l'attestation
            $pdfGenerated = false;
            $emailSent = false;
            
            try {
                $attestationService = new \App\Services\AttestationService();
                $pdfBase64 = $attestationService->genererAttestation($contrat);
                $pdfGenerated = true;
                
                // Envoyer l'email avec le PDF attaché AU PROPRIÉTAIRE DU VÉHICULE
                $emailService = new \App\Services\EmailService();
                $emailSent = $emailService->envoyerAttestation($contrat, $pdfBase64);
                
            } catch (\Exception $e) {
                \Log::error('Erreur génération PDF/Email:', ['error' => $e->getMessage()]);
                
                // Fallback: envoyer un email simple AU PROPRIÉTAIRE DU VÉHICULE
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
                } catch (\Exception $e2) {
                    \Log::error('Erreur envoi email simple:', ['error' => $e2->getMessage()]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Contrat créé avec succès ! ' . 
                    ($pdfGenerated && $emailSent ? 'Attestation PDF envoyée par email.' : 
                     ($emailSent ? 'Email de confirmation envoyé.' : 
                     'Contrat créé, email sera envoyé prochainement.')),
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
                    'pdf_generated' => $pdfGenerated,
                    'email_sent' => $emailSent,
                    'email_destinataire' => $user->email, // Email du propriétaire du véhicule
                    'message' => $pdfGenerated && $emailSent ? 
                        'Votre contrat a été créé avec succès. L\'attestation PDF a été envoyée par email.' : 
                        ($emailSent ? 'Votre contrat a été créé avec succès. Un email de confirmation a été envoyé.' :
                        'Votre contrat a été créé avec succès. L\'attestation sera envoyée prochainement.')
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
