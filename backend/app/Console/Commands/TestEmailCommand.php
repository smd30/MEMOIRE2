<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contrat;
use App\Services\AttestationService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending with latest contract';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST EMAIL ENVOI');
        $this->info('==================');
        
        // Récupérer le dernier contrat
        $contrat = Contrat::with(['user', 'vehicule', 'compagnie'])->latest()->first();
        
        if (!$contrat) {
            $this->error('❌ Aucun contrat trouvé');
            return;
        }
        
        $this->info("📄 Contrat trouvé: {$contrat->numero_attestation}");
        $this->info("📧 Email du propriétaire: {$contrat->vehicule->proprietaire_email}");
        
        // Configurer l'email avec SMTP réel
        config(['mail.default' => 'smtp']);
        config(['mail.mailers.smtp.transport' => 'smtp']);
        config(['mail.mailers.smtp.host' => 'smtp.gmail.com']);
        config(['mail.mailers.smtp.port' => 587]);
        config(['mail.mailers.smtp.username' => 'kdsassur@gmail.com']);
        config(['mail.mailers.smtp.password' => 'drta mgti ioxp hwwo']);
        config(['mail.mailers.smtp.encryption' => 'tls']);
        config(['mail.from.address' => 'kdsassur@gmail.com']);
        config(['mail.from.name' => 'KDS Assurance']);
        $this->info('📧 Configuration email avec SMTP Gmail');
        
        try {
            // Générer le PDF
            $attestationService = new AttestationService();
            $pdfBase64 = $attestationService->genererAttestation($contrat);
            $this->info('✅ PDF généré');
            
            // Test avec EmailService
            $this->info('📧 Test avec EmailService...');
            
            $emailService = new EmailService();
            $emailSent = $emailService->envoyerAttestation($contrat, $pdfBase64);
            
            if ($emailSent) {
                $this->info('✅ Email "envoyé" avec succès (mode LOG) !');
                $this->info('📁 Vérifiez le fichier storage/logs/laravel.log');
            } else {
                $this->error('❌ L\'email n\'a pas été envoyé');
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur: {$e->getMessage()}");
            $this->error("🔍 Trace: {$e->getTraceAsString()}");
        }
        
        $this->info('🏁 Test terminé');
    }
}
