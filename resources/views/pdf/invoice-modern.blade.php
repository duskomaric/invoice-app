<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #111827; margin: 0; padding: 0; }
        .page { padding: 28px; }
        .muted { color: #6b7280; }
        .small { font-size: 11px; }
        .row { display: flex; justify-content: space-between; gap: 16px; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; background: #ffffff; }
        .topbar { background: #111827; color: #ffffff; border-radius: 12px; padding: 16px 18px; margin-bottom: 16px; }
        .title { font-size: 22px; font-weight: 800; margin: 0; letter-spacing: .02em; }
        .pill { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; background: #e5e7eb; color: #111827; }
        .pill.draft { background: #e5e7eb; color: #111827; }
        .pill.sent { background: #dbeafe; color: #1e40af; }
        .pill.paid { background: #dcfce7; color: #166534; }
        .pill.overdue { background: #fee2e2; color: #991b1b; }
        .pill.partial { background: #fef9c3; color: #854d0e; }

        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { text-align: left; font-size: 11px; color: #6b7280; border-bottom: 1px solid #e5e7eb; padding: 10px 8px; text-transform: uppercase; letter-spacing: .04em; }
        td { padding: 12px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #fafafa; }
        .right { text-align: right; }
        .desc { font-weight: 700; color: #111827; }
        .subdesc { margin-top: 2px; font-size: 11px; color: #6b7280; }
        .totalsWrap { display: flex; justify-content: flex-end; margin-top: 16px; }
        .totalsCard { width: 320px; }
        .totalsRow { display: flex; justify-content: space-between; padding: 6px 0; }
        .totalsRow + .totalsRow { border-top: 1px solid #f3f4f6; }
        .grand { padding-top: 10px; margin-top: 10px; border-top: 1px solid #e5e7eb; font-size: 15px; font-weight: 800; }
    </style>
</head>
<body>
<div class="page">
    <div class="topbar">
        <div class="row">
            <div>
                <div class="title">{{ __('invoice.invoice') }}</div>
                <div class="small muted" style="color: #e5e7eb; margin-top: 6px;">
                    {{ __('invoice.invoice') }} #: <strong style="color:#ffffff;">{{ $invoice->id }}</strong><span style="color:#9ca3af;"> &nbsp;|&nbsp; </span>
                    {{ __('invoice.date') }}: <strong style="color:#ffffff;">{{ $invoice->date->format('M d, Y') }}</strong><span style="color:#9ca3af;"> &nbsp;|&nbsp; </span>
                    {{ __('invoice.due_date') }}: <strong style="color:#ffffff;">{{ $invoice->due_date?->format('M d, Y') }}</strong>
                </div>
            </div>
            <div style="text-align:right;">
                <span class="pill {{ strtolower($invoice->status->value) }}">{{ __('invoice.' . strtolower($invoice->status->value)) }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card" style="flex: 1;">
            <div style="font-weight: 800; font-size: 14px; margin-bottom: 8px;">{{ __('invoice.to') }}:</div>
            <div class="small" style="color:#111827;">
                <div style="font-size: 14px; font-weight: 800; margin-bottom: 4px;">{{ $invoice->client->name }}</div>
                <div class="muted">
                    {{ $invoice->client->address }}<br>
                    {{ $invoice->client->city }}, {{ $invoice->client->zip }}<br>
                    {{ $invoice->client->country }}<br>
                    {{ $invoice->client->email }}
                </div>
            </div>
        </div>

        <div class="card" style="width: 260px;">
            <div style="font-weight: 800; font-size: 14px; margin-bottom: 8px;">{{ \App\Models\Setting::get('company_name', config('app.name')) }}</div>
            <div class="small muted" style="color:#111827;">
                {!! nl2br(e(\App\Models\Setting::get('company_address'))) !!}<br>
                @if(\App\Models\Setting::get('company_email'))
                    {{ \App\Models\Setting::get('company_email') }}<br>
                @endif
                @if(\App\Models\Setting::get('company_phone'))
                    {{ \App\Models\Setting::get('company_phone') }}<br>
                @endif
                @if(\App\Models\Setting::get('company_vat_id'))
                    {{ \App\Models\Setting::get('company_vat_id') }}<br>
                @endif

                @if(isset($bankAccounts) && $bankAccounts && $bankAccounts->isNotEmpty())
                    <div style="margin-top: 8px; font-weight: 800;">{{ __('invoice.bank') }}</div>
                    @foreach($bankAccounts as $bankAccount)
                        <div style="margin-top: 6px;">
                            <div style="font-weight: 700;">{{ $bankAccount->bank_name }}</div>
                            <div>{{ $bankAccount->account_number }}</div>
                            @if($bankAccount->swift)
                                <div class="muted">SWIFT: {{ $bankAccount->swift }}</div>
                            @endif
                        </div>
                    @endforeach
                @elseif(isset($bankAccount) && $bankAccount)
                    <div style="margin-top: 8px; font-weight: 800;">{{ __('invoice.bank') }}</div>
                    <div style="margin-top: 6px;">
                        <div style="font-weight: 700;">{{ $bankAccount->bank_name }}</div>
                        <div>{{ $bankAccount->account_number }}</div>
                        @if($bankAccount->swift)
                            <div class="muted">SWIFT: {{ $bankAccount->swift }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>{{ __('invoice.description') }}</th>
            <th class="right">{{ __('invoice.quantity') }}</th>
            <th class="right">{{ __('invoice.price') }}</th>
            <th class="right">{{ __('invoice.total') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $item)
            <tr>
                <td>
                    <div class="desc">{{ $item->name }}</div>
                    @if($item->description)
                        <div class="subdesc">{{ $item->description }}</div>
                    @endif
                </td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">{{ number_format($item->unit_price / 100, 2) }}</td>
                <td class="right">{{ number_format($item->total / 100, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totalsWrap">
        <div class="card totalsCard">
            <div class="totalsRow">
                <div class="muted">{{ __('invoice.subtotal') }}</div>
                <div>{{ number_format($invoice->subtotal / 100, 2) }} {{ $invoice->currency }}</div>
            </div>
            <div class="totalsRow">
                <div class="muted">{{ __('invoice.tax') }}</div>
                <div>{{ number_format($invoice->tax / 100, 2) }} {{ $invoice->currency }}</div>
            </div>
            <div class="totalsRow grand">
                <div>{{ __('invoice.total') }}</div>
                <div>{{ number_format($invoice->total / 100, 2) }} {{ $invoice->currency }}</div>
            </div>
        </div>
    </div>

    @if($invoice->notes)
        <div class="card" style="margin-top: 16px;">
            <div style="font-weight:800; margin-bottom:6px;">Notes</div>
            <div class="muted">{{ $invoice->notes }}</div>
        </div>
    @endif
</div>
</body>
</html>
