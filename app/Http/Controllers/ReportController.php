<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Report\DailySalesReportRequest;
use App\Http\Requests\Report\MonthlySalesReportRequest;
use App\Http\Requests\Report\ProfitReportRequest;
use App\Http\Requests\Report\TopCustomersReportRequest;
use App\Queries\Report\GetDailySalesReportQuery;
use App\Queries\Report\GetMonthlySalesReportQuery;
use App\Queries\Report\GetProfitReportQuery;
use App\Queries\Report\GetTopCustomersReportQuery;
use Illuminate\View\View;

final readonly class ReportController
{
    public function __construct(
        private GetDailySalesReportQuery $dailySalesQuery,
        private GetMonthlySalesReportQuery $monthlySalesQuery,
        private GetTopCustomersReportQuery $topCustomersQuery,
        private GetProfitReportQuery $profitQuery,
    ) {}

    public function daily(DailySalesReportRequest $request): View
    {
        $date = $request->getDate();
        $data = $this->dailySalesQuery->execute($date);

        return view('reports.daily', [
            'date' => $date,
            'invoices' => $data['invoices'],
            'totalSales' => $data['total_sales'],
            'totalReturns' => $data['total_returns'],
            'totalPayments' => $data['total_payments'],
            'netSales' => $data['net_sales'],
        ]);
    }

    public function monthly(MonthlySalesReportRequest $request): View
    {
        $year = $request->getYear();
        $month = $request->getMonth();
        $data = $this->monthlySalesQuery->execute($year, $month);

        return view('reports.monthly', [
            'year' => $year,
            'month' => $month,
            'invoices' => $data['invoices'],
            'totalSales' => $data['total_sales'],
            'totalReturns' => $data['total_returns'],
            'totalPayments' => $data['total_payments'],
            'netSales' => $data['net_sales'],
        ]);
    }

    public function topCustomers(TopCustomersReportRequest $request): View
    {
        $limit = $request->getLimit();
        $customers = $this->topCustomersQuery->execute($limit);

        return view('reports.top-customers', [
            'customers' => $customers,
            'limit' => $limit,
        ]);
    }

    public function profit(ProfitReportRequest $request): View
    {
        $year = $request->getYear();
        $month = $request->getMonth();
        $data = $this->profitQuery->execute($year, $month);

        return view('reports.profit', [
            'year' => $year,
            'month' => $month,
            'revenue' => $data['revenue'],
            'returns' => $data['returns'],
            'payments' => $data['payments'],
            'netRevenue' => $data['net_revenue'],
        ]);
    }
}
