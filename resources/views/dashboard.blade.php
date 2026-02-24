@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php use App\Enums\InvoiceStatus; @endphp

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-semibold text-slate-50 mb-8">Dashboard</h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total customers -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-indigo-500/20 text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-slate-50">{{ $stats['total_customers'] }}</span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Total customers</h3>
            </div>

            <!-- Total Revenue -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-green-500/20 text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-slate-50">${{ $stats['total_revenue'] }}</span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Total Revenue</h3>
            </div>

            <!-- Draft Invoices -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-slate-500/20 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <span
                        class="text-3xl font-bold text-slate-50">{{ $stats['invoices_by_status'][InvoiceStatus::Draft->value] ?? 0 }}</span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Draft Invoices</h3>
            </div>

            <!-- Sent Invoices -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-blue-500/20 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    <span
                        class="text-3xl font-bold text-slate-50">{{ $stats['invoices_by_status'][InvoiceStatus::Sent->value] ?? 0 }}</span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Sent Invoices</h3>
            </div>
        </div>

        <!-- Second Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Paid Invoices -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-green-500/20 text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-3xl font-bold text-slate-50">{{ $stats['invoices_by_status'][InvoiceStatus::Paid->value] ?? 0 }}</span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Paid Invoices</h3>
            </div>
            <!-- Returned Invoices -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-yellow-500/20 text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h11M9 21l-6-6 6-6m12 4a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-slate-50">
                        {{ $stats['invoices_by_status'][InvoiceStatus::Returned->value] ?? 0 }}
                    </span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Returned Invoices</h3>
            </div>
            <!-- Cancelled Invoices -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-red-500/20 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-3xl font-bold text-slate-50">{{ $stats['invoices_by_status'][InvoiceStatus::Cancelled->value] ?? 0 }}</span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium">Cancelled Invoices</h3>
            </div>

        </div>
    </div>
@endsection