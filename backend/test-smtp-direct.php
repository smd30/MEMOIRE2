<?php

echo "🧪 TEST SMTP DIRECT\n";
echo "==================\n\n";

// Configuration SMTP directe
$smtpConfig = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'kdsassur@gmail.com',
    'password' => 'drta mgti ioxp hwwo',
    'encryption' => 'tls',
    'from_email' => 'kdsassur@gmail.com',
    'from_name' => 'KDS Assurance'
];

echo "📧 Configuration SMTP:\n";
echo "Host: {$smtpConfig['host']}\n";
echo "Port: {$smtpConfig['port']}\n";
echo "Username: {$smtpConfig['username']}\n";
echo "Encryption: {$smtpConfig['encryption']}\n\n";

// Test avec PHPMailer si disponible
if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    echo "✅ PHPMailer disponible\n";
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = $smtpConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['username'];
        $mail->Password = $smtpConfig['password'];
        $mail->SMTPSecure = $smtpConfig['encryption'];
        $mail->Port = $smtpConfig['port'];
        
        // Expéditeur et destinataire
        $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
        $mail->addAddress('test@example.com', 'Test User');
        
        // Contenu
        $mail->isHTML(true);
        $mail->Subject = 'Test Email SMTP';
        $mail->Body = '<h1>Test Email</h1><p>Cet email teste la configuration SMTP.</p>';
        
        // Envoi
        $mail->send();
        echo "✅ Email envoyé avec succès !\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur: {$e->getMessage()}\n";
    }
} else {
    echo "❌ PHPMailer non disponible\n";
    echo "🔍 Utilisez Laravel Mail avec la configuration SMTP\n";
}

echo "\n==========================================\n";
echo "🏁 Test terminé\n";
