<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $hoy          = Carbon::now();
        $primerDia    = $hoy->firstOfMonth()->format('Y-m-d');
        $ultimoDia    = $hoy->endOfMonth()->format('Y-m-d');
        return view('home', compact('primerDia', 'ultimoDia'));
        //return view('medic-care.index');
    }
}
