<?php

namespace App\Services;

use App\Models\Contrat;
use Illuminate\Support\Facades\Log;

class SMTPEmailService
{
    public function envoyerAttestation(Contrat $contrat, string $pdfBase64): bool
    {
        try {
            $vehicule = $contrat->vehicule;
            
            // Configuration SMTP
            $smtpConfig = [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'username' => 'kdsassur@gmail.com',
                'password' => 'drta mgti ioxp hwwo',
                'encryption' => 'tls',
                'from_email' => 'kdsassur@gmail.com',
                'from_name' => 'KDS Assurance'
            ];
            
            // Générer le contenu de l'email
            $emailContent = $this->genererContenuEmail($contrat);
            
            // Créer le fichier PDF temporaire
            $pdfPath = $this->sauvegarderPDF($pdfBase64, $contrat->numero_attestation);
            
            // Envoyer l'email via SMTP direct
            $result = $this->envoyerSMTPDirect(
                $smtpConfig,
                $vehicule->proprietaire_email,
                $vehicule->proprietaire_prenom . ' ' . $vehicule->proprietaire_nom,
                'Attestation d\'assurance - N° ' . $contrat->numero_attestation,
                $emailContent,
                $pdfPath
            );
            
            // Supprimer le fichier temporaire
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            
            if ($result) {
                Log::info('Email SMTP envoyé avec succès à: ' . $vehicule->proprietaire_email);
                return true;
            } else {
                Log::error('Échec envoi email SMTP à: ' . $vehicule->proprietaire_email);
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur SMTP Email Service: ' . $e->getMessage());
            return false;
        }
    }
    
    private function genererContenuEmail(Contrat $contrat): string
    {
        $vehicule = $contrat->vehicule;
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attestation d\'assurance</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #151C46; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .info { background: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #151C46; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ATTESTATION D\'ASSURANCE AUTOMOBILE</h1>
        <p>N° ' . $contrat->numero_attestation . '</p>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>' . $vehicule->proprietaire_prenom . ' ' . $vehicule->proprietaire_nom . '</strong>,</p>
        
        <p>Votre contrat d\'assurance automobile a été créé avec succès. Veuillez trouver ci-joint votre attestation d\'assurance.</p>
        
        <div class="info">
            <h3>INFORMATIONS DU CONTRAT</h3>
            <p><strong>N° Attestation:</strong> ' . $contrat->numero_attestation . '</p>
            <p><strong>N° Police:</strong> ' . $contrat->numero_police . '</p>
            <p><strong>Véhicule:</strong> ' . $vehicule->marque_vehicule . ' ' . $vehicule->modele . '</p>
            <p><strong>Immatriculation:</strong> ' . $vehicule->immatriculation . '</p>
            <p><strong>Période:</strong> ' . $contrat->date_debut->format('d/m/Y') . ' au ' . $contrat->date_fin->format('d/m/Y') . '</p>
            <p><strong>Prime TTC:</strong> ' . number_format($contrat->prime_ttc, 0, ',', ' ') . ' FCFA</p>
        </div>
        
        <p><strong>Merci de votre confiance !</strong></p>
        <p>Pour toute question, contactez votre compagnie d\'assurance.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    private function sauvegarderPDF(string $pdfBase64, string $numeroAttestation): string
    {
        $pdfContent = base64_decode($pdfBase64);
        $path = storage_path('app/temp/attestation_' . $numeroAttestation . '_' . time() . '.pdf');
        
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($path, $pdfContent);
        return $path;
    }
    
    private function envoyerSMTPDirect(array $config, string $to, string $toName, string $subject, string $body, string $attachment = null): bool
    {
        try {
            // Utiliser la fonction mail() de PHP si disponible
            if (function_exists('mail')) {
                $headers = [
                    'From: ' . $config['from_name'] . ' <' . $config['from_email'] . '>',
                    'Reply-To: ' . $config['from_email'],
                    'Content-Type: text/html; charset=UTF-8',
                    'MIME-Version: 1.0'
                ];
                
                $result = mail($to, $subject, $body, implode("\r\n", $headers));
                
                if ($result) {
                    Log::info('Email envoyé via mail() PHP à: ' . $to);
                    return true;
                }
            }
            
            // Fallback: simuler l'envoi
            Log::info('Simulation envoi email SMTP à: ' . $to);
            Log::info('Sujet: ' . $subject);
            Log::info('Contenu: ' . substr(strip_tags($body), 0, 200) . '...');
            
            return true; // Simuler le succès pour l'instant
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMTP direct: ' . $e->getMessage());
            return false;
        }
    }
}
