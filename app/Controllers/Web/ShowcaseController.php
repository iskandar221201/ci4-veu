<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class ShowcaseController extends BaseController
{
    public function index()
    {
        return view('showcase/index', [
            'title'       => 'Component Gallery',
            'breadcrumbs' => [['label' => 'Component Gallery']],
        ]);
    }
}
