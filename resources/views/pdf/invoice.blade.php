<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Račun {{ $invoice->number ?? $invoice->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        /* A4 printable area */
        .page {
            width: 190mm;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .right { text-align: right; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .small { font-size: 9px; }

        .mt-5 { margin-top: 5px; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        /* horizontal rules like on original */
        .hr {
            border-top: 1px solid #000;
            margin: 8px 0;
        }

        /* ===== ITEMS TABLE ===== */

        .items {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .items th,
        .items td {
            padding: 3px 4px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* NO left/right outer borders */
        .items th:first-child,
        .items td:first-child {
            border-left: none;
        }

        .items th:last-child,
        .items td:last-child {
            border-right: none;
        }

        /* dashed vertical inner borders */
        .items th:not(:last-child),
        .items td:not(:last-child) {
            border-right: 1px dashed #000;
        }

        /* header bottom line */
        .items thead th {
            border-bottom: 1px solid #000;
            font-weight: bold;
        }

        /* NO horizontal lines between rows */
        .items tbody td {
            border-top: none;
            border-bottom: none;
        }

        /* column widths tuned for A4 */
        .col-ident { width: 8%; }
        .col-name  { width: 36%; }
        .col-qty   { width: 10%; }
        .col-unit  { width: 6%; }
        .col-price { width: 10%; }
        .col-disc  { width: 6%; }
        .col-vat   { width: 6%; }
        .col-net   { width: 18%; }

        /* totals */
        .totals td {
            padding: 3px;
        }

        .totals .line-top {
            border-top: 1px solid #000;
        }

        /* VAT recap */
        .vat {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .vat th,
        .vat td {
            padding: 3px 4px;
        }

        .vat th:not(:last-child),
        .vat td:not(:last-child) {
            border-right: 1px dashed #000;
        }

        .vat th {
            border-bottom: 1px solid #000;
        }
    </style>
</head>
<body>

<div class="page">

    {{-- HEADER --}}
    <table>
        <tr>
            <td></td>
            <td class="right small">
                <strong>{{ \App\Models\Setting::get('company_name') }}</strong><br>
                {{ \App\Models\Setting::get('company_address') }}<br>
                {{ \App\Models\Setting::get('company_zip') }}
                {{ \App\Models\Setting::get('company_city') }}<br>
                {{ \App\Models\Setting::get('company_website') }}<br><br>

                Reg. sud: {{ \App\Models\Setting::get('company_court') }}<br>
                JIB: {{ \App\Models\Setting::get('company_jib') }}<br>
                PDV: {{ \App\Models\Setting::get('company_pdv') }}
            </td>
        </tr>
    </table>

    <div class="hr"></div>

    {{-- BUYER / DELIVERY --}}
    <table class="mt-5">
        <tr>
            <td width="50%">
                <strong>Kupac:</strong><br>
                {{ $invoice->client->name }}<br>
                JIB: {{ $invoice->client->jib }}<br>
                PDV: {{ $invoice->client->vat_id }}<br>
                {{ $invoice->client->address }}<br>
                {{ $invoice->client->zip }} {{ $invoice->client->city }}
            </td>

            <td width="50%">
                <strong>Isporuka:</strong><br>
                {{ $invoice->client->name }}<br>
                JIB: {{ $invoice->client->jib }}<br>
                PDV: {{ $invoice->client->vat_id }}<br>
                {{ $invoice->client->address }}<br>
                {{ $invoice->client->zip }} {{ $invoice->client->city }}
            </td>
        </tr>
    </table>

    <div class="hr"></div>

    {{-- META --}}
    <table>
        <tr>
            <td>
                Dat. izd.: {{ $invoice->date->format('d.m.Y') }}<br>
                Datum valute: {{ $invoice->due_date->format('d.m.Y') }}<br>
                Mjesto, dan: {{ \App\Models\Setting::get('company_city') }},
                {{ $invoice->date->format('d.m.Y') }}
            </td>
            <td class="center title">Račun</td>
            <td class="right">
                Broj: {{ $invoice->number ?? $invoice->id }}<br>
                Odgovorna osoba: Administrator
            </td>
        </tr>
    </table>

    <div class="hr"></div>

    {{-- ITEMS --}}
    <table class="items mt-10">
        <thead>
        <tr>
            <th class="col-ident">Ident</th>
            <th class="col-name">Naziv</th>
            <th class="col-qty right">Količina</th>
            <th class="col-unit">MJ</th>
            <th class="col-price right">Cijena</th>
            <th class="col-disc right">R.%</th>
            <th class="col-vat right">PDV %</th>
            <th class="col-net right">Vrijednost bez PDV</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->description }}</td>
                <td class="right">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                <td>{{ $item->unit }}</td>
                <td class="right">{{ number_format($item->unit_price / 100, 2, ',', '.') }}</td>
                <td class="right">{{ $item->discount ?? 0 }}</td>
                <td class="right">17</td>
                <td class="right">{{ number_format($item->subtotal / 100, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <table class="mt-10 totals">
        <tr>
            <td width="70%"></td>
            <td>Ukupno</td>
            <td class="right">{{ number_format($invoice->subtotal / 100, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Popust</td>
            <td class="right">{{ number_format($invoice->discount / 100, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>PDV</td>
            <td class="right">{{ number_format($invoice->tax / 100, 2, ',', '.') }}</td>
        </tr>
        <tr class="bold line-top">
            <td></td>
            <td>Za platiti KM</td>
            <td class="right">{{ number_format($invoice->total / 100, 2, ',', '.') }}</td>
        </tr>
    </table>

    {{-- VAT RECAP --}}
    <table class="vat mt-20">
        <tr>
            <th>PORESKE STOPE</th>
            <th class="right">Osnova</th>
            <th class="right">PDV</th>
            <th class="right">Vrijednost</th>
        </tr>
        <tr>
            <td>PDV - isporuke/prijemi</td>
            <td class="right">{{ number_format($invoice->subtotal / 100, 2, ',', '.') }}</td>
            <td class="right">{{ number_format($invoice->tax / 100, 2, ',', '.') }}</td>
            <td class="right">{{ number_format($invoice->total / 100, 2, ',', '.') }}</td>
        </tr>
    </table>

    <p class="mt-10 small">
        Pri plaćanju platnim nalogom navesti model 12 i poziv na broj.
    </p>

</div>
</body>
</html>
