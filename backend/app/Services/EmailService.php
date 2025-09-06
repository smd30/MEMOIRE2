<?php

namespace App\Services;

use App\Models\Contrat;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailService
{
    public function envoyerAttestation(Contrat $contrat, string $pdfBase64): bool
    {
        try {
            $user = $contrat->user;
            $vehicule = $contrat->vehicule;
            $compagnie = $contrat->compagnie;

            // Préparer les données pour l'email
            $emailData = [
                'numero_attestation' => $contrat->numero_attestation,
                'numero_police' => $contrat->numero_police,
                'souscripteur' => $user->prenom . ' ' . $user->nom,
                'vehicule' => $vehicule->marque_vehicule . ' ' . $vehicule->modele,
                'immatriculation' => $vehicule->immatriculation,
                'compagnie' => $compagnie->nom,
                'date_debut' => $contrat->date_debut->format('d/m/Y'),
                'date_fin' => $contrat->date_fin->format('d/m/Y'),
                'prime_ttc' => number_format($contrat->prime_ttc, 0, ',', ' '),
                'garanties' => json_decode($contrat->garanties_selectionnees, true) ?: ['RC', 'Vol'],
            ];

            // Sauvegarder le PDF temporairement
            $pdfPath = $this->sauvegarderPDFTemporaire($pdfBase64, $contrat->numero_attestation);

            // Envoyer l'email
            Mail::send('emails.attestation', $emailData, function ($message) use ($user, $contrat, $pdfPath) {
                $message->to($user->email, $user->prenom . ' ' . $user->nom)
                        ->subject('Attestation d\'assurance - N° ' . $contrat->numero_attestation)
                        ->attach($pdfPath, [
                            'as' => 'Attestation_' . $contrat->numero_attestation . '.pdf',
                            'mime' => 'application/pdf',
                        ]);
            });

            // Supprimer le fichier temporaire
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            return true;

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'attestation par email: ' . $e->getMessage());
            return false;
        }
    }

    private function sauvegarderPDFTemporaire(string $pdfBase64, string $numeroAttestation): string
    {
        $pdfContent = base64_decode($pdfBase64);
        $tempPath = storage_path('app/temp/attestation_' . $numeroAttestation . '_' . time() . '.pdf');
        
        // Créer le dossier temp s'il n'existe pas
        $tempDir = dirname($tempPath);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        file_put_contents($tempPath, $pdfContent);
        return $tempPath;
    }

    public function envoyerEmailConfirmation(Contrat $contrat): bool
    {
        try {
            $user = $contrat->user;
            $vehicule = $contrat->vehicule;
            $compagnie = $contrat->compagnie;

            $emailData = [
                'numero_attestation' => $contrat->numero_attestation,
                'numero_police' => $contrat->numero_police,
                'souscripteur' => $user->prenom . ' ' . $user->nom,
                'vehicule' => $vehicule->marque_vehicule . ' ' . $vehicule->modele,
                'immatriculation' => $vehicule->immatriculation,
                'compagnie' => $compagnie->nom,
                'date_debut' => $contrat->date_debut->format('d/m/Y'),
                'date_fin' => $contrat->date_fin->format('d/m/Y'),
                'prime_ttc' => number_format($contrat->prime_ttc, 0, ',', ' '),
                'garanties' => json_decode($contrat->garanties_selectionnees, true) ?: ['RC', 'Vol'],
            ];

            Mail::send('emails.confirmation', $emailData, function ($message) use ($user, $contrat) {
                $message->to($user->email, $user->prenom . ' ' . $user->nom)
                        ->subject('Confirmation de souscription - Contrat N° ' . $contrat->numero_police);
            });

            return true;

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de confirmation: ' . $e->getMessage());
            return false;
        }
    }
}
