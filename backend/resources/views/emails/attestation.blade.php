<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attestation d'assurance</title>
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
            background: linear-gradient(135deg, #151C46 0%, #2a3a7a 100%);
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
        .info-section {
            margin-bottom: 25px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #151C46;
        }
        .info-title {
            font-weight: bold;
            color: #151C46;
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
        .garanties-list {
            list-style: none;
            padding: 0;
        }
        .garanties-list li {
            background: #f8f9fa;
            padding: 8px 12px;
            margin-bottom: 5px;
            border-left: 4px solid #28a745;
            border-radius: 3px;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: #e9ecef;
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #151C46;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #0f142f;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ATTESTATION D'ASSURANCE AUTOMOBILE</h1>
        <p>N° {{ $numero_attestation }}</p>
    </div>

    <div class="content">
        <p>Bonjour <strong>{{ $souscripteur }}</strong>,</p>
        
        <p>Votre contrat d'assurance automobile a été créé avec succès. Veuillez trouver ci-joint votre attestation d'assurance.</p>

        <div class="info-section">
            <div class="info-title">INFORMATIONS DU CONTRAT</div>
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
            <ul class="garanties-list">
                @if(is_array($garanties))
                    @foreach($garanties as $garantie)
                        <li>{{ $garantie }}</li>
                    @endforeach
                @else
                    <li>{{ $garanties }}</li>
                @endif
            </ul>
        </div>

        <div class="info-section">
            <div class="info-title">INFORMATIONS IMPORTANTES</div>
            <ul>
                <li>Conservez cette attestation dans votre véhicule</li>
                <li>Présentez-la en cas de contrôle routier</li>
                <li>Contactez votre compagnie en cas de sinistre</li>
                <li>Vérifiez la validité de votre contrat avant expiration</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Merci de votre confiance !</strong></p>
            <p>Pour toute question, contactez votre compagnie d'assurance.</p>
            <p style="font-size: 12px; color: #666;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>
