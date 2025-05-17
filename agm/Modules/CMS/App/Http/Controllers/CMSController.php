<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;

class CMSController extends Controller
{
    public function index()
    {
        return view('cms::dashboard');
    }

    public function show()
    {
        return view('cms::dashboard');
    }
}
