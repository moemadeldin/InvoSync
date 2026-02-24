<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\GetDashboardStatsQuery;
use Illuminate\View\View;

final readonly class DashboardController
{
    public function __construct(
        private GetDashboardStatsQuery $query,
    ) {}

    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => $this->query->execute(),
        ]);
    }
}
