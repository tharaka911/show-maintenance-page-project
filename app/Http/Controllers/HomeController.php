<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use DB;

use Illuminate\Http\Request;

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
        $domains = Domain::all();
        $domains = DB::table('domains')->paginate(20);
        return view('home', compact('domains'));
    }
}
