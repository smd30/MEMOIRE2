<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class SimpleSouscriptionController extends Controller
{
    public function souscrire(Request $request)
    {
        try {
            // Afficher les données reçues
            $data = $request->all();
            \Log::info('Données de souscription reçues:', $data);
            
            // Validation des données
            $validator = Validator::make($data, [
                'vehicule' => 'required|array',
                'compagnie_id' => 'required',
                'periode_police' => 'required',
                'date_debut' => 'required|date',
                'garanties_selectionnees' => 'required|array',
                'proprietaire' => 'required|array',
                'proprietaire.nom' => 'required|string',
                'proprietaire.prenom' => 'required|string',
                'proprietaire.email' => 'required|email',
                'proprietaire.telephone' => 'required|string',
                'proprietaire.adresse' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            // Récupérer les données avec conversion de types
            $vehiculeData = $data['vehicule'];
            $proprietaireData = $data['proprietaire'];
            $compagnieId = (int) $data['compagnie_id'];
            $periodePolice = (int) $data['periode_police'];
            $dateDebut = $data['date_debut'];

            \Log::info('Types convertis:', [
                'compagnie_id' => $compagnieId . ' (' . gettype($compagnieId) . ')',
                'periode_police' => $periodePolice . ' (' . gettype($periodePolice) . ')',
                'date_debut' => $dateDebut . ' (' . gettype($dateDebut) . ')'
            ]);

            // Récupérer la compagnie
            $compagnie = \App\Models\Compagnie::findOrFail($compagnieId);
            \Log::info('Compagnie trouvée:', ['id' => $compagnie->id, 'nom' => $compagnie->nom]);

            // Créer ou récupérer l'utilisateur propriétaire
            $user = \App\Models\User::where('email', $proprietaireData['email'])->first();
            
            if (!$user) {
                $user = \App\Models\User::create([
                    'nom' => $proprietaireData['nom'],
                    'prenom' => $proprietaireData['prenom'],
                    'email' => $proprietaireData['email'],
                    'Telephone' => $proprietaireData['telephone'],
                    'adresse' => $proprietaireData['adresse'],
                    'role' => 'client',
                    'statut' => 'actif',
                    'MotDePasse' => bcrypt('password123'),
                ]);
                \Log::info('Utilisateur créé:', ['id' => $user->id, 'email' => $user->email]);
            } else {
                \Log::info('Utilisateur existant:', ['id' => $user->id, 'email' => $user->email]);
            }

            // Créer le véhicule avec conversion de types
            $vehicule = \App\Models\Vehicule::create([
                'user_id' => $user->id,
                'marque_vehicule' => $vehiculeData['marque_vehicule'],
                'modele' => $vehiculeData['modele'],
                'immatriculation' => $vehiculeData['immatriculation'],
                'puissance_fiscale' => (int) $vehiculeData['puissance_fiscale'],
                'date_mise_en_circulation' => $vehiculeData['date_mise_en_circulation'],
                'valeur_vehicule' => (int) $vehiculeData['valeur_vehicule'],
                'energie' => $vehiculeData['energie'],
                'places' => (int) $vehiculeData['places'],
                'numero_chassis' => $vehiculeData['numero_chassis'],
                'categorie' => 'voiture_particuliere',
                'proprietaire_nom' => $user->nom,
                'proprietaire_prenom' => $user->prenom,
                'proprietaire_adresse' => $user->adresse,
                'proprietaire_telephone' => $user->Telephone,
                'proprietaire_email' => $user->email,
            ]);
            \Log::info('Véhicule créé:', ['id' => $vehicule->id, 'immatriculation' => $vehicule->immatriculation]);

            // Générer les numéros (simplifiés)
            $numeroPolice = 'POL' . time();
            $numeroAttestation = 'ATT' . time();
            $cleSecurite = 'KEY' . time();

            // Calculer les dates
            $dateDebutContrat = \Carbon\Carbon::parse($dateDebut);
            $dateFinContrat = $dateDebutContrat->copy()->addMonths($periodePolice);
            \Log::info('Dates calculées:', [
                'debut' => $dateDebutContrat->toDateString(),
                'fin' => $dateFinContrat->toDateString()
            ]);

            // Créer le contrat avec conversion de types
            $contrat = \App\Models\Contrat::create([
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id,
                'compagnie_id' => $compagnieId,
                'numero_police' => $numeroPolice,
                'numero_attestation' => $numeroAttestation,
                'cle_securite' => $cleSecurite,
                'date_debut' => $dateDebutContrat,
                'date_fin' => $dateFinContrat,
                'periode_police' => $periodePolice,
                'garanties_selectionnees' => json_encode($data['garanties_selectionnees']),
                'prime_rc' => (float) $data['devis_calcule']['prime_rc'],
                'garanties_optionnelles' => (float) $data['devis_calcule']['garanties_optionnelles'],
                'accessoires_police' => (float) $data['devis_calcule']['accessoires_police'],
                'prime_nette' => (float) $data['devis_calcule']['prime_nette'],
                'taxes_tuca' => (float) $data['devis_calcule']['taxes_tuca'],
                'prime_ttc' => (float) $data['devis_calcule']['prime_ttc'],
                'statut' => 'actif',
                'date_souscription' => now(),
            ]);
            \Log::info('Contrat créé:', ['id' => $contrat->id, 'numero_police' => $contrat->numero_police]);

            // Envoyer l'email de confirmation
            $emailSent = false;
            try {
                // Email simple sans template
                $emailContent = "Bonjour {$user->prenom} {$user->nom},\n\n";
                $emailContent .= "Votre contrat d'assurance a été créé avec succès.\n\n";
                $emailContent .= "Détails du contrat :\n";
                $emailContent .= "- N° Police: {$contrat->numero_police}\n";
                $emailContent .= "- N° Attestation: {$contrat->numero_attestation}\n";
                $emailContent .= "- Véhicule: {$vehicule->marque_vehicule} {$vehicule->modele}\n";
                $emailContent .= "- Immatriculation: {$vehicule->immatriculation}\n";
                $emailContent .= "- Période: {$contrat->date_debut->format('d/m/Y')} au {$contrat->date_fin->format('d/m/Y')}\n";
                $emailContent .= "- Prime TTC: " . number_format($contrat->prime_ttc, 0, ',', ' ') . " FCFA\n";
                $emailContent .= "- Garanties: " . implode(', ', json_decode($contrat->garanties_selectionnees, true)) . "\n\n";
                $emailContent .= "Cordialement,\nKDS Assurances";

                Mail::raw($emailContent, function ($message) use ($user, $contrat) {
                    $message->to($user->email, $user->prenom . ' ' . $user->nom)
                            ->subject('Attestation d\'assurance - N° ' . $contrat->numero_attestation)
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });

                $emailSent = true;
                \Log::info('Email envoyé avec succès:', ['email' => $user->email]);
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email:', ['error' => $e->getMessage()]);
            }

            DB::commit();

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
                    'compagnie' => [
                        'nom' => $compagnie->nom
                    ],
                    'attestation_generated' => true,
                    'email_sent' => $emailSent,
                    'message' => $emailSent ? 
                        'Votre contrat a été créé avec succès. L\'attestation a été envoyée par email.' : 
                        'Votre contrat a été créé avec succès. L\'attestation sera envoyée prochainement.'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Erreur dans SimpleSouscriptionController:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du contrat',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}