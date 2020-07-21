<?php

namespace App\Http\Controllers;

use App\Loan;

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
        $loans = auth()->user()->hasRole('superadmin') ? Loan::latest() : auth()->user()->loans()->latest();

        $data = [
            'loans' => $loans->with('user', 'approval')->get(),
        ];

        return view('pages.dashboard.index', $data);
    }
}
