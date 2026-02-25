@extends('layouts.app')

@section('title', 'Profit Report')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Profit Report</h1>
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
            <div class="bg-indigo-900/30 border border-indigo-700/50 rounded-xl p-6">
                <p class="text-indigo-400 text-sm font-medium">Revenue (Invoices)</p>
                <p class="text-2xl font-bold text-indigo-400 mt-2">${{ number_format($revenue, 2) }}</p>
            </div>
            <div class="bg-red-900/30 border border-red-700/50 rounded-xl p-6">
                <p class="text-red-400 text-sm font-medium">Sales Returns</p>
                <p class="text-2xl font-bold text-red-400 mt-2">${{ number_format($returns, 2) }}</p>
            </div>
            <div class="bg-green-900/30 border border-green-700/50 rounded-xl p-6">
                <p class="text-green-400 text-sm font-medium">Payments Received</p>
                <p class="text-2xl font-bold text-green-400 mt-2">${{ number_format($payments, 2) }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-slate-400 text-sm font-medium">Net Revenue</p>
                <p class="text-2xl font-bold text-slate-50 mt-2">${{ number_format($netRevenue, 2) }}</p>
            </div>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 shadow-lg">
            <h3 class="text-lg font-semibold text-slate-50 mb-4">Summary</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-slate-900 rounded-lg">
                    <span class="text-slate-400">Total Revenue Generated</span>
                    <span class="text-slate-50 font-semibold">${{ number_format($revenue, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-900 rounded-lg">
                    <span class="text-slate-400">Less: Sales Returns</span>
                    <span class="text-red-400 font-semibold">-${{ number_format($returns, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-900 rounded-lg">
                    <span class="text-slate-400">Add: Payments Collected</span>
                    <span class="text-green-400 font-semibold">+${{ number_format($payments, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-indigo-900/30 border border-indigo-700/50 rounded-lg">
                    <span class="text-indigo-400 font-semibold">Net Revenue</span>
                    <span class="text-indigo-400 font-bold text-xl">${{ number_format($netRevenue, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
