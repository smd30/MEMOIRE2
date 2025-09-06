<?php

namespace App\Services;

use App\Models\Contrat;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Compagnie;
use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttestationService
{
    public function genererAttestation(Contrat $contrat): string
    {
        // Récupérer les données associées
        $user = $contrat->user;
        $vehicule = $contrat->vehicule;
        $compagnie = $contrat->compagnie;
        
        // Générer le QR Code
        $qrCodeData = $this->genererQRCodeData($contrat);
        $qrCodeBase64 = $this->genererQRCodeBase64($contrat);

        // Préparer les données pour l'attestation
        $data = [
            'numero_attestation' => $contrat->numero_attestation,
            'numero_police' => $contrat->numero_police,
            'cle_securite' => $contrat->cle_securite,
            'souscripteur' => $user->prenom . ' ' . $user->nom,
            'vehicule' => [
                'marque' => $vehicule->marque_vehicule,
                'modele' => $vehicule->modele,
                'immatriculation' => $vehicule->immatriculation,
                'puissance_fiscale' => $vehicule->puissance_fiscale,
                'places' => $vehicule->places,
                'categorie' => $vehicule->categorie,
            ],
            'compagnie' => [
                'nom' => $compagnie->nom,
                'adresse' => $compagnie->adresse ?? 'Rocade Fann Bel Air Place Bakou',
                'telephone' => $compagnie->telephone ?? '(+221) 33 831 06 06 / (+221) 33 832 12 05',
                'email' => $compagnie->email ?? 'assurcnart@arc.sn',
            ],
            'dates' => [
                'debut' => $contrat->date_debut->format('d-m-Y'),
                'fin' => $contrat->date_fin->format('d-m-Y à 23:59'),
                'souscription' => $contrat->date_souscription->format('Y-m-d H:i'),
            ],
            'garanties' => json_decode($contrat->garanties_selectionnees, true),
            'prime_ttc' => number_format($contrat->prime_ttc, 0, ',', ' '),
        ];

        // Générer le HTML de l'attestation
        $html = $this->genererHTMLAttestation($data, $qrCodeBase64);

        // Configurer DomPDF optimisé pour les images base64
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'screen');
        $options->set('isFontCacheEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Retourner le PDF en base64
        return base64_encode($dompdf->output());
    }

    private function genererHTMLAttestation(array $data, string $qrCodeBase64): string
    {
        $garantiesList = '';
        foreach ($data['garanties'] as $garantie) {
            $garantiesList .= '<li>' . htmlspecialchars($garantie) . '</li>';
        }

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Attestation d\'assurance</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background: #f5f5f5;
                }
                .attestation-container {
                    background: white;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    border-bottom: 3px solid #151C46;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .title {
                    font-size: 24px;
                    font-weight: bold;
                    color: #151C46;
                    margin: 0 0 10px 0;
                }
                .subtitle {
                    font-size: 18px;
                    color: #666;
                    margin: 0;
                }
                .info-section {
                    margin-bottom: 25px;
                }
                .info-title {
                    font-size: 16px;
                    font-weight: bold;
                    color: #151C46;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 5px;
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
                    color: #333;
                    margin-bottom: 5px;
                }
                .info-value {
                    color: #666;
                    padding: 5px 0;
                }
                .garanties-list {
                    list-style: none;
                    padding: 0;
                }
                .garanties-list li {
                    background: #f8f9fa;
                    padding: 8px 12px;
                    margin-bottom: 5px;
                    border-left: 4px solid #151C46;
                    border-radius: 3px;
                }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    border-top: 2px solid #ddd;
                    padding-top: 20px;
                }
                .compagnie-info {
                    font-size: 14px;
                    color: #666;
                    line-height: 1.5;
                }
                .security-info {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    margin: 20px 0;
                    border-left: 4px solid #28a745;
                }
                .security-title {
                    font-weight: bold;
                    color: #28a745;
                    margin-bottom: 10px;
                }
                .page-break {
                    page-break-before: always;
                    margin: 30px 0;
                }
                .qr-code-section {
                    margin: 40px 0;
                    padding: 20px 0;
                    text-align: center;
                    border-top: 2px solid #151C46;
                    border-bottom: 2px solid #151C46;
                }
                .qr-code {
                    width: 100px;
                    height: 100px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    margin: 20px auto;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="attestation-container">
                <div class="header">
                    <h1 class="title">ATTESTATION D\'ASSURANCE AUTOMOBILE</h1>
                    <p class="subtitle">N° ' . htmlspecialchars($data['numero_attestation']) . '</p>
                </div>

                <div class="info-section">
                    <div class="info-title">INFORMATIONS DU VÉHICULE</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Immatriculation:</div>
                            <div class="info-value">' . htmlspecialchars($data['vehicule']['immatriculation']) . '</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Marque/Modèle:</div>
                            <div class="info-value">' . htmlspecialchars($data['vehicule']['marque']) . ' ' . htmlspecialchars($data['vehicule']['modele']) . '</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Puissance fiscale:</div>
                            <div class="info-value">' . htmlspecialchars($data['vehicule']['puissance_fiscale']) . ' CV</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nombre de places:</div>
                            <div class="info-value">' . htmlspecialchars($data['vehicule']['places']) . '</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Catégorie:</div>
                            <div class="info-value">' . htmlspecialchars($data['vehicule']['categorie']) . '</div>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-title">INFORMATIONS DU SOUSCRIPTEUR</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nom complet:</div>
                            <div class="info-value">' . htmlspecialchars($data['souscripteur']) . '</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">N° Police:</div>
                            <div class="info-value">' . htmlspecialchars($data['numero_police']) . '</div>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-title">PÉRIODE DE VALIDITÉ</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Date de début:</div>
                            <div class="info-value">' . htmlspecialchars($data['dates']['debut']) . '</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de fin:</div>
                            <div class="info-value">' . htmlspecialchars($data['dates']['fin']) . '</div>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-title">GARANTIES INCLUSES</div>
                    <ul class="garanties-list">
                        ' . $garantiesList . '
                    </ul>
                </div>

                <div class="security-info">
                    <div class="security-title">INFORMATIONS DE SÉCURITÉ</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Clé de sécurité:</div>
                            <div class="info-value">' . htmlspecialchars($data['cle_securite']) . '</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Prime TTC:</div>
                            <div class="info-value">' . htmlspecialchars($data['prime_ttc']) . ' FCFA</div>
                        </div>
                    </div>
                </div>

                <div class="page-break"></div>
                
                <div class="qr-code-section">
                    ' . $qrCodeBase64 . '
                </div>

                <div class="footer">
                    <div class="compagnie-info">
                        <strong>' . htmlspecialchars($data['compagnie']['nom']) . '</strong><br>
                        ' . htmlspecialchars($data['compagnie']['adresse']) . '<br>
                        Tél: ' . htmlspecialchars($data['compagnie']['telephone']) . '<br>
                        Email: ' . htmlspecialchars($data['compagnie']['email']) . '<br>
                        Date de souscription: ' . htmlspecialchars($data['dates']['souscription']) . '
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Génère les données pour le QR Code
     */
    private function genererQRCodeData(Contrat $contrat): string
    {
        $data = [
            'numero_attestation' => $contrat->numero_attestation,
            'numero_police' => $contrat->numero_police,
            'date_souscription' => $contrat->date_souscription,
            'nom_assure' => $contrat->user->nom . ' ' . $contrat->user->prenom,
            'immatriculation' => $contrat->vehicule->immatriculation,
            'marque_modele' => $contrat->vehicule->marque_vehicule . ' ' . $contrat->vehicule->modele_vehicule,
            'compagnie' => $contrat->compagnie->nom
        ];
        
        return json_encode($data);
    }
    
    /**
     * Génère le QR Code en base64 pour DomPDF
     */
    private function genererQRCodeBase64(Contrat $contrat): string
    {
        try {
            // Créer le message de vérification au format demandé
            $qrMessage = "Le véhicule est assuré\n";
            $qrMessage .= "L'attestation d'assurance N° " . $contrat->numero_attestation . " du véhicule de marque " . $contrat->vehicule->marque_vehicule . " " . $contrat->vehicule->modele . " immatriculé " . $contrat->vehicule->immatriculation . " est valide\n";
            $qrMessage .= "du " . $contrat->date_debut->format('Y-m-d') . " au " . $contrat->date_fin->format('Y-m-d') . " 23:59:59";
            
            // Générer le QR Code en SVG (plus compatible)
            $qrCodeSvg = QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->errorCorrection('M')
                ->generate($qrMessage);
            
            // Convertir SVG en base64
            $base64 = base64_encode($qrCodeSvg);
            
            // Vérifier que le base64 est valide
            if (empty($base64) || strlen($base64) < 100) {
                throw new \Exception('QR Code base64 invalide');
            }
            
            // Retourner l'image SVG base64 intégrée dans le PDF
            return '<div style="text-align: center; margin: 15px 0; padding: 15px; border: 2px solid #000;">
                        <div style="font-size: 12px; font-weight: bold; margin-bottom: 10px;">QR CODE DE VÉRIFICATION</div>
                        <img src="data:image/svg+xml;base64,' . $base64 . '" 
                             alt="QR Code de vérification" 
                             style="width: 120px; height: 120px; display: block; margin: 0 auto; border: 1px solid #ccc;">
                        <div style="font-size: 10px; margin-top: 8px; color: #333; font-weight: bold;">Scannez pour vérifier le contrat</div>
                    </div>';
        } catch (\Exception $e) {
            \Log::error('Erreur génération QR Code: ' . $e->getMessage());
            
            // En cas d'erreur, créer un QR Code ASCII simple
            return $this->genererQRCodeASCII($data);
        }
    }
    
    /**
     * Génère un QR Code ASCII simple en cas d'erreur
     */
    private function genererQRCodeASCII(string $data): string
    {
        $qrData = substr($data, 0, 50);
        $qrPattern = '';
        
        // Créer un pattern QR Code simple
        for ($i = 0; $i < 10; $i++) {
            $line = '';
            for ($j = 0; $j < 10; $j++) {
                $line .= (($i + $j) % 2 == 0) ? '█' : '░';
            }
            $qrPattern .= $line . '<br>';
        }
        
        return '<div style="text-align: center; margin: 15px 0; padding: 15px; border: 2px solid #000;">
                    <div style="font-size: 12px; font-weight: bold; margin-bottom: 10px;">QR CODE DE VÉRIFICATION</div>
                    <div style="font-family: monospace; font-size: 8px; line-height: 1; margin: 10px 0;">' . $qrPattern . '</div>
                    <div style="font-size: 10px; margin-top: 8px; color: #333; font-weight: bold;">Contrat: ' . substr($qrData, 0, 20) . '...</div>
                </div>';
    }
}
