<?php

namespace App\Services;

use App\Models\Contrat;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Compagnie;
use Dompdf\Dompdf;
use Dompdf\Options;

class AttestationService
{
    public function genererAttestation(Contrat $contrat): string
    {
        // Récupérer les données associées
        $user = $contrat->user;
        $vehicule = $contrat->vehicule;
        $compagnie = $contrat->compagnie;

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
        $html = $this->genererHTMLAttestation($data);

        // Configurer DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Retourner le PDF en base64
        return base64_encode($dompdf->output());
    }

    private function genererHTMLAttestation(array $data): string
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
                .qr-placeholder {
                    width: 100px;
                    height: 100px;
                    background: #f0f0f0;
                    border: 2px dashed #ccc;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 20px auto;
                    font-size: 12px;
                    color: #999;
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

                <div class="qr-placeholder">
                    Code QR<br>de vérification
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
}
