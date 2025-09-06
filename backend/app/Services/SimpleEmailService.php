<?php

namespace App\Services;

use App\Models\Contrat;
use Illuminate\Support\Facades\Log;

class SimpleEmailService
{
    public function envoyerAttestation(Contrat $contrat, string $pdfBase64): bool
    {
        try {
            $vehicule = $contrat->vehicule;
            
            // Simuler l'envoi d'email en sauvegardant les informations
            $emailData = [
                'to' => $vehicule->proprietaire_email,
                'to_name' => $vehicule->proprietaire_prenom . ' ' . $vehicule->proprietaire_nom,
                'subject' => 'Attestation d\'assurance - N° ' . $contrat->numero_attestation,
                'body' => $this->genererCorpsEmail($contrat),
                'attachment' => 'Attestation_' . $contrat->numero_attestation . '.pdf',
                'sent_at' => now()->format('Y-m-d H:i:s'),
                'status' => 'sent'
            ];
            
            // Sauvegarder dans les logs
            Log::info('Email envoyé (simulation):', $emailData);
            
            // Sauvegarder le PDF pour référence
            $this->sauvegarderPDF($pdfBase64, $contrat->numero_attestation);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi email simple: ' . $e->getMessage());
            return false;
        }
    }
    
    private function genererCorpsEmail(Contrat $contrat): string
    {
        $vehicule = $contrat->vehicule;
        $user = $contrat->user;
        
        $body = "Bonjour {$vehicule->proprietaire_prenom} {$vehicule->proprietaire_nom},\n\n";
        $body .= "Votre attestation d'assurance automobile a été créée avec succès.\n\n";
        $body .= "Détails du contrat :\n";
        $body .= "- N° Attestation: {$contrat->numero_attestation}\n";
        $body .= "- N° Police: {$contrat->numero_police}\n";
        $body .= "- Véhicule: {$vehicule->marque_vehicule} {$vehicule->modele}\n";
        $body .= "- Immatriculation: {$vehicule->immatriculation}\n";
        $body .= "- Période: {$contrat->date_debut->format('d/m/Y')} au {$contrat->date_fin->format('d/m/Y')}\n";
        $body .= "- Prime TTC: " . number_format($contrat->prime_ttc, 0, ',', ' ') . " FCFA\n\n";
        $body .= "Veuillez trouver votre attestation en pièce jointe.\n\n";
        $body .= "Merci de votre confiance !\n";
        $body .= "KDS Assurance";
        
        return $body;
    }
    
    private function sauvegarderPDF(string $pdfBase64, string $numeroAttestation): void
    {
        $pdfContent = base64_decode($pdfBase64);
        $path = storage_path('app/emails/attestation_' . $numeroAttestation . '_' . time() . '.pdf');
        
        // Créer le dossier s'il n'existe pas
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($path, $pdfContent);
    }
}
