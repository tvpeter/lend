<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('loans')->withCount('loans')->orderBy('name')->get();
        
        return view('pages.reports.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = User::with('loans')->findOrFail($id);
        
        return view('pages.reports.show', compact('staff'));
    }
}
