@extends('layouts.app')

@section('title', 'Monthly Sales Report')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Monthly Sales Report</h1>
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        <form method="GET" class="mb-6 flex items-center gap-4">
            <select name="year" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-50">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select name="month" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-50">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endfor
            </select>
            <button type="submit"
                class="px-4 py-2 rounded-lg text-white font-semibold text-sm gradient-primary">
                Filter
            </button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-slate-400 text-sm font-medium">Total Sales</p>
                <p class="text-2xl font-bold text-slate-50 mt-2">${{ number_format($totalSales, 2) }}</p>
            </div>
            <div class="bg-red-900/30 border border-red-700/50 rounded-xl p-6">
                <p class="text-red-400 text-sm font-medium">Total Returns</p>
                <p class="text-2xl font-bold text-red-400 mt-2">${{ number_format($totalReturns, 2) }}</p>
            </div>
            <div class="bg-green-900/30 border border-green-700/50 rounded-xl p-6">
                <p class="text-green-400 text-sm font-medium">Total Payments</p>
                <p class="text-2xl font-bold text-green-400 mt-2">${{ number_format($totalPayments, 2) }}</p>
            </div>
            <div class="bg-indigo-900/30 border border-indigo-700/50 rounded-xl p-6">
                <p class="text-indigo-400 text-sm font-medium">Net Sales</p>
                <p class="text-2xl font-bold text-indigo-400 mt-2">${{ number_format($netSales, 2) }}</p>
            </div>
        </div>

        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Invoice #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-700/50">
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $invoice->customer->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded {{ $invoice->status->badgeClass() }}">
                                    {{ $invoice->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-slate-200">${{ number_format((float) $invoice->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No invoices found for this month.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
