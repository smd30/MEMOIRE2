<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Contrat;
use App\Models\Compagnie;
use App\Services\AttestationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestSouscriptionController extends Controller
{
    protected $attestationService;

    public function __construct(AttestationService $attestationService)
    {
        $this->attestationService = $attestationService;
    }

    public function testSouscription(Request $request)
    {
        try {
            $data = $request->all();
            
            // 1. Créer ou récupérer l'utilisateur
            $user = User::firstOrCreate(
                ['email' => $data['proprietaire']['email']],
                [
                    'nom' => $data['proprietaire']['nom'] ?? 'Test',
                    'prenom' => $data['proprietaire']['prenom'] ?? 'User',
                    'adresse' => $data['proprietaire']['adresse'] ?? 'Adresse test',
                    'Telephone' => $data['proprietaire']['telephone'] ?? '123456789',
                    'password' => Hash::make('password123'),
                ]
            );

            // 2. Créer le véhicule
            $immatriculation = $data['vehicule']['immatriculation'] ?? 'TEST' . time();
            while (Vehicule::where('immatriculation', $immatriculation)->exists()) {
                $immatriculation = 'TEST' . time() . rand(1000, 9999);
            }

            $vehicule = Vehicule::create([
                'user_id' => $user->id,
                'marque_vehicule' => $data['vehicule']['marque_vehicule'] ?? 'PEUGEOT',
                'modele' => $data['vehicule']['modele'] ?? '206',
                'immatriculation' => $immatriculation,
                'puissance_fiscale' => (int) ($data['vehicule']['puissance_fiscale'] ?? 6),
                'date_mise_en_circulation' => $data['vehicule']['date_mise_en_circulation'] ?? '2010-01-15',
                'valeur_vehicule' => (int) ($data['vehicule']['valeur_vehicule'] ?? 5000000),
                'energie' => $data['vehicule']['energie'] ?? 'essence',
                'places' => (int) ($data['vehicule']['places'] ?? 5),
                'numero_chassis' => $data['vehicule']['numero_chassis'] ?? 'VF3XXXXXXXXXXXXXXX' . time() . rand(1000, 9999),
                'categorie' => 'voiture_particuliere',
                'proprietaire_nom' => $user->nom,
                'proprietaire_prenom' => $user->prenom,
                'proprietaire_adresse' => $user->adresse,
                'proprietaire_telephone' => $user->Telephone,
                'proprietaire_email' => $user->email,
            ]);

            // 3. Créer le contrat
            $contrat = Contrat::create([
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id,
                'compagnie_id' => $data['compagnie_id'] ?? 1,
                'numero_police' => 'POL' . time(),
                'numero_attestation' => 'ATT' . time(),
                'date_debut' => $data['date_debut'] ?? now(),
                'date_fin' => $data['date_fin'] ?? now()->addYear(),
                'garanties_selectionnees' => json_encode($data['garanties_selectionnees'] ?? ['RC', 'Vol']),
                'prime_rc' => (float) ($data['devis_calcule']['prime_rc'] ?? 3405),
                'garanties_optionnelles' => (float) ($data['devis_calcule']['garanties_optionnelles'] ?? 200),
                'accessoires_police' => (float) ($data['devis_calcule']['accessoires_police'] ?? 2000),
                'prime_nette' => (float) ($data['devis_calcule']['prime_nette'] ?? 5605),
                'taxes_tuca' => (float) ($data['devis_calcule']['taxes_tuca'] ?? 1065),
                'prime_ttc' => (float) ($data['devis_calcule']['prime_ttc'] ?? 6670),
                'statut' => 'actif',
                'date_souscription' => now(),
                'cle_securite' => 'KEY' . time(),
                'periode_police' => $data['periode_police'] ?? '1',
            ]);

            // 4. Générer le PDF de l'attestation
            $pdfBase64 = null;
            $pdfGenerated = false;
            
            try {
                $pdfBase64 = $this->attestationService->genererAttestation($contrat);
                $pdfGenerated = true;
            } catch (\Exception $e) {
                \Log::error('Erreur génération PDF:', ['error' => $e->getMessage()]);
                $pdfGenerated = false;
                // Retourner une erreur JSON au lieu de continuer
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur génération PDF: ' . $e->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contrat créé avec succès !',
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
                    'email_sent' => false,
                    'email_destinataire' => $user->email,
                    'attestation_pdf' => $pdfGenerated ? $pdfBase64 : null,
                    'pdf_filename' => $pdfGenerated ? 'Attestation_' . $contrat->numero_attestation . '.pdf' : null,
                    'qr_code_data' => $this->genererQRCodeData($contrat, $vehicule, $user)
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
    
    /**
     * Génère les données pour le QR Code
     */
    private function genererQRCodeData($contrat, $vehicule, $user): array
    {
        return [
            'contrat_id' => $contrat->id,
            'numero_attestation' => $contrat->numero_attestation,
            'numero_police' => $contrat->numero_police,
            'vehicule_marque' => $vehicule->marque_vehicule,
            'vehicule_modele' => $vehicule->modele,
            'immatriculation' => $vehicule->immatriculation,
            'date_debut' => $contrat->date_debut->format('Y-m-d'),
            'date_fin' => $contrat->date_fin->format('Y-m-d'),
            'proprietaire_nom' => $user->nom,
            'proprietaire_prenom' => $user->prenom,
            'prime_ttc' => $contrat->prime_ttc,
            'verification_url' => url('/verification/' . $contrat->numero_attestation)
        ];
    }
}