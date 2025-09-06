<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Email</title>
</head>
<body>
    <h1>Test Email</h1>
    <p>Bonjour {{ $souscripteur }},</p>
    <p>Votre attestation {{ $numero_attestation }} a été créée.</p>
    <p>Véhicule: {{ $vehicule }}</p>
    <p>Immatriculation: {{ $immatriculation }}</p>
    <p>Garanties: {{ implode(', ', $garanties) }}</p>
</body>
</html>
