<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class UserWebController extends BaseController
{
    public function loginPage()
    {
        return view('auth/login');
    }

    public function index()
    {
        return view('users/index', [
            'title'       => 'Daftar Users',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Users'],
            ],
            'action' => ['label' => 'Tambah User', 'url' => '/users/create'],
        ]);
    }

    public function create()
    {
        return view('users/create', [
            'title'       => 'Tambah User',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Users', 'url' => '/users'],
                ['label' => 'Tambah'],
            ],
        ]);
    }

    public function detail($id)
    {
        return view('users/detail', [
            'id'          => $id,
            'title'       => 'Detail User',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Users', 'url' => '/users'],
                ['label' => 'Detail'],
            ],
        ]);
    }
}
