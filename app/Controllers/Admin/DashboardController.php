<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Request;
use App\Core\View;
use App\Models\PortfolioModel;

final class DashboardController
{
    public function index(Request $request): string
    {
        Auth::require();
        return View::render('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => (new PortfolioModel())->stats(),
            'modules' => require dirname(__DIR__, 3) . '/config/modules.php',
        ], 'layouts/admin');
    }
}
