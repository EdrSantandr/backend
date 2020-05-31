<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        return view('landing');
    }
}
