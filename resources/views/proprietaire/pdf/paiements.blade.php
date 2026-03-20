<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevé de Paiements</title>
    <style>
        @page {
            margin: 50px 40px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header-left {
            float: left;
            width: 60%;
        }

        .header-right {
            float: right;
            width: 35%;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        h1 {
            color: #1e3a8a;
            font-size: 22px;
            margin: 0 0 5px 0;
        }

        h2 {
            color: #1e3a8a;
            font-size: 16px;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-row {
            margin-bottom: 8px;
            display: flex;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
            color: #1e3a8a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background-color: #1e3a8a;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 11px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .total-row {
            background-color: #dbeafe !important;
            font-weight: bold;
            border-top: 2px solid #1e3a8a;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paye {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-attente {
            background-color: #fef3c7;
            color: #d97706;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>RELEVÉ DE PAIEMENTS</h1>
            <p>Espace Propriétaire - Plateforme Immobilière</p>
            <p>Document généré le : {{ $dateGeneration }}</p>
        </div>
        <div class="header-right">
            <p><strong>Propriétaire :</strong></p>
            <p>{{ $proprietaire->nom }}</p>
            <p>{{ $proprietaire->email }}</p>
            @if($proprietaire->telephone)
            <p>Tél: {{ $proprietaire->indicatif_pays }} {{ $proprietaire->telephone }}</p>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="info-box">
        <h2>Période concernée</h2>
        <div class="info-row">
            <div class="info-label">Période :</div>
            <div>{{ $periode }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nombre de paiements :</div>
            <div>{{ $paiements->count() }}</div>
        </div>
        @if($avecTotal)
        <div class="info-row">
            <div class="info-label">Montant total :</div>
            <div><strong>{{ number_format($total, 0, ',', ' ') }} FCFA</strong></div>
        </div>
        @endif
    </div>

    <h2>Détail des paiements</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 20%;">Locataire</th>
                <th style="width: 25%;">Bien</th>
                <th style="width: 15%;">Type</th>
                <th style="width: 15%;">Montant</th>
                <th style="width: 10%;">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paiements as $paiement)
            <tr>
                <td>{{ $paiement->created_at->format('d/m/Y') }}</td>
                <td>{{ $paiement->utilisateur->nom ?? 'N/A' }}</td>
                <td>
                    @if($paiement->maison)
                        {{ $paiement->maison->nom ?? 'Maison' }}
                    @elseif($paiement->appartement)
                        Appartement n°{{ $paiement->appartement->numero_appartement ?? 'N/A' }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($paiement->maison)
                        Maison
                    @elseif($paiement->appartement)
                        Appartement
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                <td>
                    <span class="status-badge status-paye">
                        {{ ucfirst($paiement->statut ?? 'payé') }}
                    </span>
                </td>
            </tr>
            @endforeach

            @if($avecTotal && $paiements->count() > 0)
            <tr class="total-row">
                <td colspan="4" style="text-align: right;"><strong>TOTAL :</strong></td>
                <td colspan="2"><strong>{{ number_format($total, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($paiements->isEmpty())
    <div style="text-align: center; padding: 40px; color: #64748b;">
        <p>Aucun paiement trouvé pour cette période</p>
    </div>
    @endif

    <div class="signature">
        <p>Signature et cachet</p>
        <p>___________________________________</p>
        <p style="margin-top: 5px; font-size: 10px; color: #64748b;">
            Document émis électroniquement - Pas de signature requise
        </p>
    </div>

    <div class="footer">
        Page 1 sur 1 • Document généré automatiquement par la plateforme immobilière
    </div>
</body>
</html>
