<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 0; }
        .page { padding: 26px; }
        .row { display: flex; justify-content: space-between; gap: 18px; }
        .muted { color: #6b7280; font-size: 11px; }
        .title { font-size: 20px; font-weight: 800; letter-spacing: .02em; }
        .divider { height: 1px; background: #e5e7eb; margin: 14px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; text-transform: uppercase; font-size: 10px; letter-spacing: .06em; color: #6b7280; border-bottom: 1px solid #e5e7eb; padding: 10px 6px; }
        td { border-bottom: 1px solid #f3f4f6; padding: 10px 6px; vertical-align: top; }
        .right { text-align: right; }
        .desc { font-weight: 700; }
        .subdesc { margin-top: 2px; font-size: 11px; color: #6b7280; }
        .totals { width: 280px; margin-left: auto; margin-top: 12px; }
        .totalsRow { display: flex; justify-content: space-between; padding: 6px 0; }
        .totalsRow + .totalsRow { border-top: 1px solid #f3f4f6; }
        .grand { margin-top: 8px; padding-top: 10px; border-top: 1px solid #e5e7eb; font-weight: 800; font-size: 14px; }
    </style>
</head>
<body>
<div class="page">
    <div class="row">
        <div>
            <div class="title">{{ __('invoice.invoice') }}</div>
            <div class="muted">#{{ $invoice->id }}</div>
        </div>
        <div style="text-align:right;">
            <div style="font-weight: 800;">{{ \App\Models\Setting::get('company_name', config('app.name')) }}</div>
            <div class="muted">
                @if(isset($bankAccounts) && $bankAccounts && $bankAccounts->isNotEmpty())
                    @foreach($bankAccounts as $bankAccount)
                        {{ $bankAccount->account_number }}@if($bankAccount->swift) / {{ $bankAccount->swift }}@endif
                        @if(! $loop->last)
                            <br>
                        @endif
                    @endforeach
                @elseif(isset($bankAccount) && $bankAccount)
                    {{ $bankAccount->account_number }}@if($bankAccount->swift) / {{ $bankAccount->swift }}@endif
                @endif
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div style="flex: 1;">
            <div class="muted">{{ __('invoice.to') }}</div>
            <div style="margin-top: 4px;">
                <div style="font-weight: 800;">{{ $invoice->client->name }}</div>
                <div class="muted">{{ $invoice->client->email }}</div>
            </div>
        </div>
        <div style="text-align:right; min-width: 220px;">
            <div class="muted">{{ __('invoice.date') }}</div>
            <div style="font-weight: 700;">{{ $invoice->date->format('M d, Y') }}</div>
            <div class="muted" style="margin-top: 6px;">{{ __('invoice.due_date') }}</div>
            <div style="font-weight: 700;">{{ $invoice->due_date?->format('M d, Y') }}</div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>{{ __('invoice.description') }}</th>
            <th class="right">{{ __('invoice.quantity') }}</th>
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
                <td class="right">{{ number_format($item->total / 100, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totals">
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

    @if($invoice->notes)
        <div class="divider"></div>
        <div class="muted">{{ $invoice->notes }}</div>
    @endif
</div>
</body>
</html>
