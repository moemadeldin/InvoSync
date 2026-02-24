<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 40px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .heading {
            font-size: 22px;
            font-weight: bold;
        }

        .subtle {
            color: #777;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f2f2f2;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .totals-table {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }

        .totals-table td {
            border: none;
            padding: 5px 0;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px solid #333;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 3px;
            background: #eee;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <table class="mb-30">
        <tr>
            <td>
                <div class="heading">
                    {{ config('app.name', 'Invoice System') }}
                </div>
                <div class="subtle">
                    {{ $invoice->user->name ?? 'Your address' }}
                </div>
            </td>
            <td class="text-right">
                <div class="heading">INVOICE</div>
                <div>Invoice #: {{ $invoice->invoice_number }}</div>
                <div>Issued: {{ $invoice->created_at->format('M d, Y') }}</div>
                <div>Due: {{ $invoice->due_date?->format('M d, Y') ?? 'N/A' }}</div>
                <div class="badge">
                    {{ $invoice->status->label() }}
                </div>
            </td>
        </tr>
    </table>

    {{-- customer --}}
    <div class="mb-30">
        <strong>Bill To:</strong><br>
        Customer Name:{{ $invoice->customer->name }}<br>
        Customer Email:{{ $invoice->customer->email }}<br>
        @if($invoice->customer->phone)
            Customer Phone:{{ $invoice->customer->phone }}<br>
        @endif
        @if($invoice->customer->address)
            Customer Address:{{ $invoice->customer->address }}<br>
        @endif
    </div>

    {{-- Items --}}
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">${{ $item->formatted_unit_price }}</td>
                    <td class="text-right">${{ $item->formatted_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">${{ $invoice->formatted_subtotal }}</td>
        </tr>
        <tr>
            <td>Tax:</td>
            <td class="text-right">${{ $invoice->formatted_tax }}</td>
        </tr>
        <tr class="total-row">
            <td>Total:</td>
            <td class="text-right">${{ $invoice->formatted_total }}</td>
        </tr>
    </table>

    {{-- Notes --}}
    @if($invoice->notes)
        <div class="mb-30">
            <strong>Notes:</strong><br>
            <span class="subtle">{{ $invoice->notes }}</span>
        </div>
    @endif

    <div class="text-center subtle" style="margin-top: 40px;">
        Thank you for your business!
    </div>

</body>

</html>