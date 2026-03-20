<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement</title>
    <style>
        @page { margin: 30px 30px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 3px solid #1e3a8a;
            margin-bottom: 12px;
        }
        .title { color: #1e3a8a; font-size: 20px; font-weight: bold; margin: 0 0 2px 0; }
        .subtitle { color: #64748b; font-size: 11px; margin: 0; }
        .montant-box {
            background-color: #1e3a8a;
            color: white;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 12px;
        }
        .montant-label { font-size: 10px; margin-bottom: 2px; }
        .montant-valeur { font-size: 22px; font-weight: bold; }
        .two-col { width: 100%; margin-bottom: 12px; }
        .col-left { float: left; width: 48%; }
        .col-right { float: right; width: 48%; }
        .clear { clear: both; }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 8px 10px;
        }
        .card-title {
            color: #1e3a8a;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
            text-transform: uppercase;
        }
        .info-row { margin-bottom: 4px; }
        .info-label { font-weight: bold; color: #1e3a8a; }
        .section-title {
            color: #1e3a8a;
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 6px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
            text-transform: uppercase;
        }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .details-table td { padding: 5px 8px; border: 1px solid #e5e7eb; font-size: 10px; }
        .details-table .label { font-weight: bold; background-color: #f1f5f9; width: 22%; color: #1e3a8a; }
        .code-recu { text-align: center; margin-bottom: 10px; }
        .code-recu-inner {
            display: inline-block;
            background: #dbeafe;
            border: 2px dashed #3b82f6;
            border-radius: 6px;
            padding: 6px 16px;
            font-family: monospace;
            font-size: 13px;
            letter-spacing: 2px;
        }
        .validation {
            text-align: center;
            padding-top: 8px;
            border-top: 2px dashed #e5e7eb;
            font-size: 10px;
            color: #64748b;
        }
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #64748b;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">REÇU DE PAIEMENT</div>
        <div class="subtitle">Plateforme Immobilière — Transaction Validée</div>
    </div>

    <div class="montant-box">
        <div class="montant-label">MONTANT REÇU</div>
        <div class="montant-valeur">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>
    </div>

    <div class="two-col">
        <div class="col-left">
            <div class="info-card">
                <div class="card-title">Propriétaire</div>
                <div class="info-row"><span class="info-label">Nom : </span>{{ $proprietaire->nom }}</div>
                <div class="info-row"><span class="info-label">Email : </span>{{ $proprietaire->email }}</div>
                @if($proprietaire->telephone)
                <div class="info-row"><span class="info-label">Tél : </span>{{ $proprietaire->indicatif_pays ?? '' }} {{ $proprietaire->telephone }}</div>
                @endif
                <div class="info-row"><span class="info-label">ID : </span>PROP-{{ str_pad($proprietaire->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
        <div class="col-right">
            <div class="info-card">
                <div class="card-title">Locataire</div>
                <div class="info-row"><span class="info-label">Nom : </span>{{ $paiement->utilisateur->nom ?? 'N/A' }}</div>
                <div class="info-row"><span class="info-label">Email : </span>{{ $paiement->utilisateur->email ?? 'N/A' }}</div>
                @if($paiement->utilisateur->telephone ?? false)
                <div class="info-row"><span class="info-label">Tél : </span>{{ $paiement->utilisateur->indicatif_pays ?? '' }} {{ $paiement->utilisateur->telephone }}</div>
                @endif
                <div class="info-row"><span class="info-label">ID : </span>LOC-{{ str_pad($paiement->utilisateur_id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section-title">Détails de la transaction</div>
    <table class="details-table">
        <tr>
            <td class="label">N° transaction</td>
            <td>TRX-{{ str_pad($paiement->id, 8, '0', STR_PAD_LEFT) }}</td>
            <td class="label">Date</td>
            <td>{{ $paiement->created_at->format('d/m/Y à H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Bien concerné</td>
            <td colspan="3">
                @if($paiement->maison)
                    Maison : {{ $paiement->maison->nom ?? 'N/A' }} — {{ $paiement->maison->adresse ?? '' }}
                @elseif($paiement->appartement)
                    Appt n°{{ $paiement->appartement->numero_appartement ?? 'N/A' }} — {{ $paiement->appartement->adresse ?? '' }}
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Méthode</td>
            <td>{{ $paiement->operateur->nom ?? 'Paiement mobile' }}</td>
            <td class="label">Statut</td>
            <td style="color: #059669; font-weight: bold;">✓ PAYÉ ET VALIDÉ</td>
        </tr>
        <tr>
            <td class="label">Référence</td>
            <td colspan="3">{{ $paiement->reference_transaction ?? $paiement->reference ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="code-recu">
        <div class="code-recu-inner">
            Code reçu : RECU-{{ strtoupper(substr(md5($paiement->id . $paiement->created_at), 0, 12)) }}
        </div>
    </div>

    <div class="validation">
        <p>Ce document certifie que le paiement a été reçu et enregistré dans notre système.</p>
        <p>Généré le : {{ $dateGeneration }} — Valide comme justificatif de paiement.</p>
    </div>

    <div class="footer">
        Reçu électronique • Valide sans signature • Plateforme Immobilière
    </div>

</body>
</html>
