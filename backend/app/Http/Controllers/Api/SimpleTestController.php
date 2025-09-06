<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Contrat;
use App\Services\AttestationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SimpleTestController extends Controller
{
    protected $attestationService;

    public function __construct(AttestationService $attestationService)
    {
        $this->attestationService = $attestationService;
    }

    public function testQRCode(Request $request)
    {
        try {
            // Créer un utilisateur simple
            $user = User::firstOrCreate(
                ['email' => 'test@example.com'],
                [
                    'nom' => 'Test',
                    'prenom' => 'User',
                    'adresse' => 'Adresse test',
                    'Telephone' => '123456789',
                    'password' => Hash::make('password123'),
                    'MotDePasse' => Hash::make('password123'),
                    'role' => 'client',
                    'statut' => 'actif',
                ]
            );

            // Créer un véhicule simple avec des données réalistes
            $vehicule = Vehicule::firstOrCreate(
                ['immatriculation' => 'DK4964AF'],
                [
                    'user_id' => $user->id,
                    'marque_vehicule' => 'PEUGEOT',
                    'modele' => '206',
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
                ]
            );

            // Créer un contrat simple
            $contrat = Contrat::create([
                'user_id' => $user->id,
                'vehicule_id' => $vehicule->id,
                'compagnie_id' => 1,
                'numero_police' => 'POL' . time(),
                'numero_attestation' => 'ATT' . time(),
                'date_debut' => now(),
                'date_fin' => now()->addYear(),
                'garanties_selectionnees' => json_encode(['RC', 'Vol']),
                'prime_rc' => 3405,
                'garanties_optionnelles' => 200,
                'accessoires_police' => 2000,
                'prime_nette' => 5605,
                'taxes_tuca' => 1065,
                'prime_ttc' => 6670,
                'statut' => 'actif',
                'date_souscription' => now(),
                'cle_securite' => 'KEY' . time(),
                'periode_police' => '1',
            ]);

            // Générer le PDF avec QR Code
            $pdfBase64 = $this->attestationService->genererAttestation($contrat);

            return response()->json([
                'success' => true,
                'message' => 'PDF généré avec QR Code !',
                'data' => [
                    'contrat_id' => $contrat->id,
                    'numero_attestation' => $contrat->numero_attestation,
                    'pdf_generated' => true,
                    'attestation_pdf' => $pdfBase64,
                    'pdf_filename' => 'Attestation_' . $contrat->numero_attestation . '.pdf',
                    'qr_code_data' => [
                        'contrat_id' => $contrat->id,
                        'numero_attestation' => $contrat->numero_attestation,
                        'immatriculation' => $vehicule->immatriculation,
                        'verification_url' => url('/verification/' . $contrat->numero_attestation)
                    ]
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