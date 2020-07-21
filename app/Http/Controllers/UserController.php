<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $users = User::where('name', '!=', 'Superadmin')->orderBy('name')->withCount('loans', 'permissions')->with('roles', 'line_manager')->get();

        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'Superadmin')->get();
        
        $permissions = Permission::all();
        
        $lineManagers = User::role('line-manager')->orderBy('name')->get();

        return view('pages.users.edit', compact('roles', 'permissions', 'lineManagers'))->with('staff', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {   
        $user->syncRoles($request->roles);
        
        $user->syncPermissions($request->permissions);
        
        $user->update([
            'line_manager_id' => $request->line_manager_id
        ]);
        
        return back()->with('message', messageResponse('success', 'User updated successfully'));
    }
    
     /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $staff = User::whereId($user)->with('roles', 'permissions', 'line_manager')->first() ?? abort(404);
        
        $staff['loans'] = $staff->loans()->with('user')->latest()->paginate(50);

        return view('pages.users.show', compact('staff'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
