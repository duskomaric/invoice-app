<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .company-details {
            float: right;
            text-align: right;
        }
        .company-details h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .invoice-details {
            float: left;
        }
        .invoice-details h1 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 32px;
            letter-spacing: 1px;
        }
        .client-details {
            margin-bottom: 40px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .client-details h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 16px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            float: right;
            width: 350px;
        }
        .totals table th {
            background-color: transparent;
            color: #555;
            text-align: left;
            padding: 5px 10px;
            border: none;
        }
        .totals table td {
            padding: 5px 10px;
            border: none;
        }
        .totals .total-row td {
            border-top: 2px solid #2c3e50;
            font-weight: bold;
            font-size: 18px;
            color: #2c3e50;
            padding-top: 10px;
        }
        .notes {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-style: italic;
            color: #666;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }
        .badge-paid { background-color: #28a745; }
        .badge-sent { background-color: #007bff; }
        .badge-overdue { background-color: #dc3545; }
        .badge-draft { background-color: #6c757d; }
        .badge-partial { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header clearfix">
            <div class="invoice-details">
                <h1>{{ __('invoice.invoice') }}</h1>
                <p>
                    <strong>{{ __('invoice.invoice') }} #:</strong> {{ $invoice->id }}<br>
                    <strong>{{ __('invoice.date') }}:</strong> {{ $invoice->date->format('M d, Y') }}<br>
                    <strong>{{ __('invoice.due_date') }}:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                    <span class="badge badge-{{ strtolower($invoice->status->value) }}">
                        {{ __('invoice.' . strtolower($invoice->status->value)) }}
                    </span>
                </p>
            </div>
            <div class="company-details">
                <h2>{{ \App\Models\Setting::get('company_name', config('app.name')) }}</h2>
                <p>
                    {!! nl2br(e(\App\Models\Setting::get('company_address'))) !!}<br>
                    @if(\App\Models\Setting::get('company_email'))
                        {{ __('invoice.email') }}: {{ \App\Models\Setting::get('company_email') }}<br>
                    @endif
                    @if(\App\Models\Setting::get('company_phone'))
                        {{ __('invoice.phone') }}: {{ \App\Models\Setting::get('company_phone') }}<br>
                    @endif
                    @if(\App\Models\Setting::get('company_vat_id'))
                        {{ __('invoice.vat_id') }}: {{ \App\Models\Setting::get('company_vat_id') }}<br>
                    @endif
                    @if(\App\Models\Setting::get('company_bank_account'))
                        {{ __('invoice.bank_account') }}: {{ \App\Models\Setting::get('company_bank_account') }}
                    @endif
                </p>
            </div>
        </div>

        <div class="client-details">
            <h3>{{ __('invoice.to') }}:</h3>
            <p>
                <strong>{{ $invoice->client->name }}</strong><br>
                {{ $invoice->client->address }}<br>
                {{ $invoice->client->city }}, {{ $invoice->client->zip }}<br>
                {{ $invoice->client->country }}<br>
                {{ __('invoice.email') }}: {{ $invoice->client->email }}
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('invoice.description') }}</th>
                    <th class="text-right">{{ __('invoice.quantity') }}</th>
                    <th class="text-right">{{ __('invoice.price') }}</th>
                    <th class="text-right">{{ __('invoice.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price / 100, 2) }}</td>
                    <td class="text-right">{{ number_format($item->total / 100, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals clearfix">
            <table>
                <tr>
                    <td><strong>{{ __('invoice.subtotal') }}:</strong></td>
                    <td class="text-right">{{ number_format($invoice->subtotal / 100, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('invoice.tax') }} (0%):</strong></td>
                    <td class="text-right">{{ number_format($invoice->tax / 100, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>{{ __('invoice.total') }}:</strong></td>
                    <td class="text-right">{{ number_format($invoice->total / 100, 2) }} BAM</td>
                </tr>
            </table>
        </div>

        @if($invoice->notes)
        <div class="notes">
            <strong>Notes:</strong> {{ $invoice->notes }}
        </div>
        @endif

        <div class="footer">
            <p>{{ \App\Models\Setting::get('company_name') }}</p>
        </div>
    </div>
</body>
</html>
