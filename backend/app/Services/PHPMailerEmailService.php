<?php

namespace App\Services;

use App\Models\Contrat;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHPMailerEmailService
{
    public function envoyerAttestation(Contrat $contrat, string $pdfBase64): bool
    {
        try {
            $vehicule = $contrat->vehicule;
            $user = $contrat->user;

            // Créer une nouvelle instance PHPMailer
            $mail = new PHPMailer(true);

            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kdsassur@gmail.com';
            $mail->Password = 'drta mgti ioxp hwwo';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Expéditeur
            $mail->setFrom('kdsassur@gmail.com', 'KDS Assurance');
            $mail->addReplyTo('kdsassur@gmail.com', 'KDS Assurance');

            // Destinataire
            $mail->addAddress($vehicule->proprietaire_email, $vehicule->proprietaire_prenom . ' ' . $vehicule->proprietaire_nom);

            // Sujet
            $mail->Subject = 'Attestation d\'assurance - N° ' . $contrat->numero_attestation;

            // Corps de l'email
            $mail->isHTML(false); // Email en texte brut
            $mail->Body = $this->genererCorpsEmail($contrat);

            // Ajouter la pièce jointe PDF
            $pdfContent = base64_decode($pdfBase64);
            $pdfFilename = 'Attestation_' . $contrat->numero_attestation . '.pdf';
            $mail->addStringAttachment($pdfContent, $pdfFilename, 'base64', 'application/pdf');

            // Envoyer l'email
            $mail->send();

            Log::info('Email envoyé avec succès via PHPMailer à: ' . $vehicule->proprietaire_email);
            return true;

        } catch (Exception $e) {
            Log::error('Erreur PHPMailer: ' . $mail->ErrorInfo);
            Log::error('Message d\'erreur: ' . $e->getMessage());
            return false;
        }
    }

    private function genererCorpsEmail(Contrat $contrat): string
    {
        $vehicule = $contrat->vehicule;
        
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

        return $message;
    }
}
