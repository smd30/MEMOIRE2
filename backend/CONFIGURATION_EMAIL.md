# 📧 Configuration Email - KDS Assurances

## Configuration du fichier .env

Créez ou modifiez votre fichier `.env` dans le dossier `backend` avec la configuration suivante :

### Option 1 : Configuration Gmail

```env
# Configuration Email Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="KDS Assurances"
```

### Option 2 : Configuration Mailtrap (pour les tests)

```env
# Configuration Email Mailtrap
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kdsassurances.com
MAIL_FROM_NAME="KDS Assurances"
```

## Configuration Gmail

### 1. Activer l'authentification à 2 facteurs
- Allez dans les paramètres de votre compte Google
- Sécurité → Authentification à 2 facteurs
- Activez l'authentification à 2 facteurs

### 2. Générer un mot de passe d'application
- Allez dans les paramètres de votre compte Google
- Sécurité → Authentification à 2 facteurs
- Mots de passe d'application
- Générez un mot de passe pour "Mail"
- Utilisez ce mot de passe dans votre .env

## Configuration Mailtrap

### 1. Créer un compte
- Allez sur [Mailtrap.io](https://mailtrap.io)
- Créez un compte gratuit

### 2. Créer une boîte de réception
- Créez une nouvelle boîte de réception
- Copiez les paramètres SMTP

### 3. Utiliser les paramètres
- Utilisez les paramètres SMTP dans votre .env
- Les emails seront capturés dans Mailtrap (pas envoyés réellement)

## Test de la configuration

### 1. Démarrer le serveur Laravel
```bash
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Tester l'envoi d'email
```bash
php test-email.php
```

### 3. Tester la souscription complète
```bash
php test-complete-souscription-final.php
```

## Vérification des logs

Si l'envoi d'email échoue, vérifiez les logs Laravel :

```bash
tail -f storage/logs/laravel.log
```

## Dépannage

### Erreur "Authentication failed"
- Vérifiez vos identifiants Gmail
- Assurez-vous d'utiliser un mot de passe d'application
- Vérifiez que l'authentification à 2 facteurs est activée

### Erreur "Connection refused"
- Vérifiez que le serveur SMTP est accessible
- Vérifiez le port et l'encryption
- Vérifiez votre connexion internet

### Erreur "Invalid credentials"
- Vérifiez le nom d'utilisateur et le mot de passe
- Pour Gmail, utilisez votre adresse email complète
- Pour Mailtrap, utilisez les identifiants fournis

## Templates d'email

Les templates d'email sont situés dans :
- `resources/views/emails/attestation.blade.php`
- `resources/views/emails/confirmation.blade.php`

## Services

- `App\Services\EmailService` : Service principal pour l'envoi d'emails
- `App\Services\AttestationService` : Service pour la génération d'attestations PDF
