<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement #{{ $paiement->reference_transaction }}</title>
    <style>
        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 12px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }

        /* En-tête */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #166534;
        }

        .logo-section {
            flex: 1;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #166534;
            margin-bottom: 5px;
        }

        .tagline {
            font-size: 10px;
            color: #666;
        }

        .document-info {
            text-align: right;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #166534;
            margin-bottom: 10px;
        }

        .reference {
            font-size: 12px;
            color: #666;
        }

        /* Infos principales */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-box {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .info-box h3 {
            color: #166534;
            font-size: 13px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #166534;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px dotted #ddd;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 140px;
        }

        .info-value {
            color: #333;
            text-align: right;
            flex: 1;
        }

        /* Section paiement */
        .paiement-section {
            margin-bottom: 30px;
        }

        .paiement-header {
            background: #166534;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            border-radius: 5px 5px 0 0;
        }

        .paiement-details {
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            padding: 15px;
        }

        .montant-box {
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border: 2px solid #166534;
            border-radius: 8px;
            background: #f0fdf4;
        }

        .montant-label {
            font-size: 14px;
            color: #166534;
            margin-bottom: 10px;
        }

        .montant {
            font-size: 28px;
            font-weight: bold;
            color: #166534;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .detail-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .detail-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .detail-value {
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        /* Signatures */
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #333;
            margin: 40px auto 10px;
        }

        .signature-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        /* Pied de page */
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #166534;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .footer p {
            margin-bottom: 5px;
        }

        /* Table pour les détails */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .details-table th {
            background: #f0fdf4;
            color: #166534;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .details-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        /* Responsive pour PDF */
        @page {
            margin: 20mm;
        }

        @media print {
            body {
                font-size: 10pt;
            }

            .container {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }

        /* Couleurs */
        .text-success {
            color: #166534;
        }

        .text-muted {
            color: #666;
        }

        .bg-light {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">IMMOLOC</div>
                <div class="tagline">Votre partenaire de confiance pour la location immobilière</div>
                <div style="margin-top: 10px; font-size: 10px;">
                    <div>Siège social: Cotonou, Bénin</div>
                    <div>Tél: +229 01 46 15 15 11 | Email: espoir@immoloc.com</div>
                    <div>Site web: www.immoloc.com</div>
                </div>
            </div>

            <div class="document-info">
                <div class="document-title">REÇU DE PAIEMENT</div>
                <div class="reference">Référence: <strong>{{ $paiement->reference_transaction }}</strong></div>
                <div style="margin-top: 10px; font-size: 10px;">
                    <div>Date d'émission: {{ date('d/m/Y') }}</div>
                    <div>Heure: {{ date('H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Informations client -->
        <div class="info-grid">
            <div class="info-box">
                <h3>INFORMATIONS CLIENT</h3>
                <div class="info-row">
                    <span class="info-label">Nom complet:</span>
                    <span class="info-value">{{ $paiement->utilisateur->nom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $paiement->utilisateur->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $paiement->utilisateur->indicatif_pays ?? '+225' }} {{ $paiement->utilisateur->telephone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Client depuis:</span>
                    <span class="info-value">{{ $paiement->utilisateur->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="info-box">
                <h3>INFORMATIONS PROPRIÉTAIRE</h3>
                @php
                    $proprietaire = $paiement->maison ? $paiement->maison->proprietaire : ($paiement->appartement ? $paiement->appartement->proprietaire : null);
                @endphp
                @if($proprietaire)
                <div class="info-row">
                    <span class="info-label">Nom complet:</span>
                    <span class="info-value">{{ $proprietaire->nom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $proprietaire->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $proprietaire->indicatif_pays ?? '+225' }} {{ $proprietaire->telephone }}</span>
                </div>
                @else
                <div class="info-row">
                    <span class="info-value">Informations non disponibles</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Détails du paiement -->
        <div class="paiement-section">
            <div class="paiement-header">
                DÉTAILS DU PAIEMENT
            </div>
            <div class="paiement-details">
                <!-- Montant en évidence -->
                <div class="montant-box">
                    <div class="montant-label">MONTANT PAYÉ</div>
                    <div class="montant">{{ number_format($paiement->montant, 2, ',', ' ') }} FCFA</div>
                </div>

                <!-- Grille des détails -->
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Date du paiement</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Heure</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('H:i') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Mois payé</div>
                        <div class="detail-value">{{ $paiement->mois_paye }} {{ $paiement->annee_paye }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Opérateur</div>
                        <div class="detail-value">{{ $paiement->operateur->nom ?? 'Mobile Money' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Méthode</div>
                        <div class="detail-value">Paiement Mobile</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Statut</div>
                        <div class="detail-value text-success">PAYÉ</div>
                    </div>
                </div>

                <!-- Informations du bien -->
                <table class="details-table">
                    <thead>
                        <tr>
                            <th colspan="4">INFORMATIONS DU BIEN LOUÉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Type de bien</strong></td>
                            <td>{{ $paiement->maison ? 'Maison' : 'Appartement' }}</td>
                            <td><strong>Référence bien</strong></td>
                            <td>{{ $paiement->maison ? $paiement->maison->id : $paiement->appartement->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nom/Numéro</strong></td>
                            <td>
                                @if($paiement->maison)
                                    {{ $paiement->maison->nom }}
                                @elseif($paiement->appartement)
                                    Appartement {{ $paiement->appartement->numero_appartement }}
                                @endif
                            </td>
                            <td><strong>Ville</strong></td>
                            <td>
                                @if($paiement->maison)
                                    {{ $paiement->maison->ville }}
                                @elseif($paiement->appartement)
                                    {{ $paiement->appartement->ville }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Adresse</strong></td>
                            <td colspan="3">
                                @if($paiement->maison)
                                    {{ $paiement->maison->adresse }}
                                @elseif($paiement->appartement)
                                    {{ $paiement->appartement->adresse }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Chambres</strong></td>
                            <td>
                                @if($paiement->maison)
                                    {{ $paiement->maison->nombre_chambres }}
                                @elseif($paiement->appartement)
                                    {{ $paiement->appartement->nombre_chambres }}
                                @endif
                            </td>
                            <td><strong>Prix mensuel</strong></td>
                            <td>
                                @if($paiement->maison)
                                    {{ number_format($paiement->maison->prix, 0, ',', ' ') }} FCFA
                                @elseif($paiement->appartement)
                                    {{ number_format($paiement->appartement->prix_mensuel, 0, ',', ' ') }} FCFA
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Signature du Client</div>
                <div style="margin-top: 5px; font-size: 10px;">
                    {{ $paiement->utilisateur->nom }}
                </div>
            </div>

            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Cachet & Signature ImmoLoc</div>
                <div style="margin-top: 5px; font-size: 10px;">
                    Service Client - ImmoLoc
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p><strong>IMMOLOC - Plateforme de location immobilière</strong></p>
            <p>Ce document est un reçu officiel de paiement. Conservez-le pour vos archives.</p>
            <p>Pour toute réclamation: contact@immoloc.ci | Tél: +225 27 00 00 00</p>
            <p>Document généré automatiquement le {{ date('d/m/Y à H:i') }} • Réf: {{ $paiement->reference_transaction }}</p>
            <p style="font-size: 8px; margin-top: 10px;">
                Ce reçu est valide sans signature manuscrite.
            </p>
        </div>
    </div>
</body>
</html>
