<?php

namespace App\Services;

use App\Models\Contrat;
use Illuminate\Support\Facades\Log;

class DirectEmailService
{
    public function envoyerAttestation(Contrat $contrat, string $pdfBase64): bool
    {
        try {
            $vehicule = $contrat->vehicule;
            $user = $contrat->user;

            $to = $vehicule->proprietaire_email;
            $subject = 'Attestation d\'assurance - N° ' . $contrat->numero_attestation;
            $from = 'kdsassur@gmail.com';
            $fromName = 'KDS Assurance';

            // Créer le contenu de l'email
            $message = "Bonjour {$vehicule->proprietaire_prenom} {$vehicule->proprietaire_nom},\n\n";
            $message .= "Votre attestation d'assurance automobile a été créée avec succès.\n";
            $message .= "Veuillez trouver votre attestation en pièce jointe.\n\n";
            $message .= "Détails du contrat :\n";
            $message .= "- N° Attestation: {$contrat->numero_attestation}\n";
            $message .= "- N° Police: {$contrat->numero_police}\n";
            $message .= "- Véhicule: {$vehicule->marque_vehicule} {$vehicule->modele}\n";
            $message .= "- Immatriculation: {$vehicule->immatriculation}\n";
            $message .= "- Période: {$contrat->date_debut->format('d/m/Y')} au {$contrat->date_fin->format('d/m/Y')}\n";
            $message .= "- Prime TTC: " . number_format($contrat->prime_ttc, 0, ',', ' ') . " FCFA\n\n";
            $message .= "Merci de votre confiance !\nKDS Assurance\n";

            // Sauvegarder le PDF temporairement
            $pdfPath = $this->sauvegarderPDFTemporaire($pdfBase64, $contrat->numero_attestation);

            // Créer l'email avec pièce jointe
            $boundary = md5(time());
            
            $headers = "From: \"$fromName\" <$from>\r\n";
            $headers .= "Reply-To: $from\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

            $body = "--$boundary\r\n";
            $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $message . "\r\n";

            // Ajouter la pièce jointe PDF
            $pdfContent = base64_decode($pdfBase64);
            $pdfFilename = 'Attestation_' . $contrat->numero_attestation . '.pdf';

            $body .= "--$boundary\r\n";
            $body .= "Content-Type: application/pdf; name=\"$pdfFilename\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$pdfFilename\"\r\n\r\n";
            $body .= chunk_split(base64_encode($pdfContent)) . "\r\n";
            $body .= "--$boundary--";

            // Configurer les paramètres SMTP
            ini_set('SMTP', 'smtp.gmail.com');
            ini_set('smtp_port', '587');
            ini_set('sendmail_from', $from);

            // Tenter d'envoyer l'email
            $mailSent = mail($to, $subject, $body, $headers);

            // Nettoyer le fichier temporaire
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            if ($mailSent) {
                Log::info('Email envoyé avec succès via DirectEmailService à: ' . $to);
                return true;
            } else {
                Log::error('Échec envoi email DirectEmailService à: ' . $to);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Erreur DirectEmailService: ' . $e->getMessage());
            Log::error('Trace complète DirectEmailService: ' . $e->getTraceAsString());
            return false;
        }
    }

    private function sauvegarderPDFTemporaire(string $pdfBase64, string $numeroAttestation): string
    {
        $pdfContent = base64_decode($pdfBase64);
        $tempPath = storage_path('app/temp/attestation_' . $numeroAttestation . '_' . time() . '.pdf');

        $tempDir = dirname($tempPath);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        file_put_contents($tempPath, $pdfContent);
        return $tempPath;
    }
}
