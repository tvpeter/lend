<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('name', '!=', 'superadmin')->latest()->withCount('users')->get();

        return view('pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,display_name',
        ]);

        Role::create([
            'name'         => Str::slug($request->name),
            'display_name' => $request->name,
        ]);

        return back()->with('message', messageResponse('success', 'Role created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('pages.roles.edit', compact('role'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($role)
    {
        $role = Role::whereName($role)->first() ?? abort(404);

        $role['users'] = $role->users()->orderBy('name')->withCount('loans', 'permissions')->with('roles')->paginate(50);

        return view('pages.roles.show', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,display_name,' . $role->id,
        ]);

        $role->update([
            'name'         => Str::slug($request->name),
            'display_name' => $request->name,
        ]);

        return redirect()->route('roles.edit', $role)->with('message', messageResponse('success', 'Role updated successfully'));
    }
}
