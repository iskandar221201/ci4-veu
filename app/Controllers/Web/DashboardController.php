<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('dashboard/index', [
            'title'       => 'Dashboard',
            'breadcrumbs' => [['label' => 'Dashboard']],
        ]);
    }
}
