<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">Invoice #{{ $invoice->invoice_number }}</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <p style="color: #374151; font-size: 16px; line-height: 1.5; margin: 0 0 20px 0;">
                                Hello {{ $invoice->customer->name }},<br><br>
                                Thank you for your business. Below are the details of your invoice.
                            </p>
                            
                            <!-- Invoice Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f9fafb; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding: 5px 0; color: #6b7280; font-size: 14px;">Invoice Number:</td>
                                                <td style="padding: 5px 0; color: #111827; font-size: 14px; font-weight: 600; text-align: right;">{{ $invoice->invoice_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6b7280; font-size: 14px;">Invoice Date:</td>
                                                <td style="padding: 5px 0; color: #111827; font-size: 14px; text-align: right;">{{ $invoice->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6b7280; font-size: 14px;">Due Date:</td>
                                                <td style="padding: 5px 0; color: #111827; font-size: 14px; text-align: right;">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6b7280; font-size: 14px;">Status:</td>
                                                <td style="padding: 5px 0; text-align: right;">
                                                    <span style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; 
                                                        @switch($invoice->status->value)
                                                            @case('draft')
                                                                background-color: #f3f4f6; color: #374151;
                                                                @break
                                                            @case('sent')
                                                                background-color: #dbeafe; color: #1e40af;
                                                                @break
                                                            @case('paid')
                                                                background-color: #d1fae5; color: #065f46;
                                                                @break
                                                            @case('cancelled')
                                                                background-color: #fee2e2; color: #991b1b;
                                                                @break
                                                        @endswitch
                                                    ">{{ $invoice->status->label() }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Invoice Items -->
                            <h2 style="color: #111827; font-size: 18px; font-weight: bold; margin: 0 0 15px 0;">Invoice Items</h2>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; margin-bottom: 20px;">
                                <thead>
                                    <tr style="background-color: #f3f4f6;">
                                        <th style="padding: 12px; text-align: left; color: #374151; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e5e7eb;">Description</th>
                                        <th style="padding: 12px; text-align: center; color: #374151; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e5e7eb;">Qty</th>
                                        <th style="padding: 12px; text-align: right; color: #374151; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e5e7eb;">Unit Price</th>
                                        <th style="padding: 12px; text-align: right; color: #374151; font-size: 14px; font-weight: 600; border-bottom: 2px solid #e5e7eb;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td style="padding: 12px; color: #111827; font-size: 14px; border-bottom: 1px solid #e5e7eb;">{{ $item->description }}</td>
                                            <td style="padding: 12px; color: #111827; font-size: 14px; text-align: center; border-bottom: 1px solid #e5e7eb;">{{ $item->qty }}</td>
                                            <td style="padding: 12px; color: #111827; font-size: 14px; text-align: right; border-bottom: 1px solid #e5e7eb;">${{ $item->formatted_unit_price }}</td>
                                            <td style="padding: 12px; color: #111827; font-size: 14px; text-align: right; border-bottom: 1px solid #e5e7eb;">${{ $item->formatted_total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Totals -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f9fafb; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding: 5px 0; color: #6b7280; font-size: 14px;">Subtotal:</td>
                                                <td style="padding: 5px 0; color: #111827; font-size: 14px; text-align: right;">${{ $invoice->formatted_subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6b7280; font-size: 14px;">Tax ({{ $invoice->formatted_tax_rate }}%):</td>
                                                <td style="padding: 5px 0; color: #111827; font-size: 14px; text-align: right;">${{ $invoice->formatted_tax }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0 5px 0; color: #111827; font-size: 16px; font-weight: bold; border-top: 1px solid #e5e7eb;">Total Amount:</td>
                                                <td style="padding: 10px 0 5px 0; color: #6366f1; font-size: 16px; font-weight: bold; text-align: right; border-top: 1px solid #e5e7eb;">${{ $invoice->formatted_total }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Notes -->
                            @if($invoice->notes)
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fef3c7; border-radius: 6px; margin-bottom: 20px;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <p style="color: #92400e; font-size: 14px; font-weight: 600; margin: 0 0 5px 0;">Notes:</p>
                                            <p style="color: #92400e; font-size: 14px; margin: 0; line-height: 1.5;">{{ $invoice->notes }}</p>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 14px; margin: 0;">Thanks,<br><strong style="color: #111827;">{{ config('app.name') }}</strong></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
