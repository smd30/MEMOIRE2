<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de souscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 25px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        .info-title {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 14px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
        }
        .next-steps {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .next-steps h3 {
            color: #007bff;
            margin-top: 0;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: #e9ecef;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="success-icon">✅</div>
        <h1>CONFIRMATION DE SOUSCRIPTION</h1>
        <p>Votre contrat d'assurance a été créé avec succès !</p>
    </div>

    <div class="content">
        <p>Bonjour <strong>{{ $souscripteur }}</strong>,</p>
        
        <p>Nous avons le plaisir de vous confirmer que votre souscription d'assurance automobile a été validée avec succès.</p>

        <div class="info-section">
            <div class="info-title">DÉTAILS DU CONTRAT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">N° Attestation:</div>
                    <div class="info-value">{{ $numero_attestation }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">N° Police:</div>
                    <div class="info-value">{{ $numero_police }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Véhicule:</div>
                    <div class="info-value">{{ $vehicule }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Immatriculation:</div>
                    <div class="info-value">{{ $immatriculation }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Compagnie:</div>
                    <div class="info-value">{{ $compagnie }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Prime TTC:</div>
                    <div class="info-value">{{ $prime_ttc }} FCFA</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-title">PÉRIODE DE VALIDITÉ</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date de début:</div>
                    <div class="info-value">{{ $date_debut }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date de fin:</div>
                    <div class="info-value">{{ $date_fin }}</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-title">GARANTIES INCLUSES</div>
            <ul>
                @foreach($garanties as $garantie)
                    <li>{{ $garantie }}</li>
                @endforeach
            </ul>
        </div>

        <div class="next-steps">
            <h3>Prochaines étapes :</h3>
            <ul>
                <li><strong>Attestation :</strong> Vous recevrez votre attestation d'assurance par email dans quelques minutes</li>
                <li><strong>Impression :</strong> Imprimez l'attestation et conservez-la dans votre véhicule</li>
                <li><strong>Contrôle :</strong> Présentez l'attestation en cas de contrôle routier</li>
                <li><strong>Contact :</strong> En cas de sinistre, contactez immédiatement votre compagnie</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Merci de votre confiance !</strong></p>
            <p>Pour toute question ou assistance, n'hésitez pas à nous contacter.</p>
            <p style="font-size: 12px; color: #666;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>
