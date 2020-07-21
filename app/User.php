<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    
    public function line_manager()
    {
        return $this->belongsTo('App\User', 'line_manager_id', 'id');
    }

    public function loans()
    {
        return $this->hasMany('App\Loan');
    }
    
    public function canCreateLoan(){
        return $this->loans()->where('mambu_id', '!=', null)->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])->count() < 3;
    }

    public function approvals()
    {
        if (auth()->user()->hasRole('line-manager')) {
            return $this->hasMany('App\LoanApproval', 'line_manager_id');
        }

        if (auth()->user()->hasRole('hr-manager')) {
            return $this->hasMany('App\LoanApproval', 'hr_manager_id');
        }

        if (auth()->user()->hasRole('cpo-manager')) {
            return $this->hasMany('App\LoanApproval', 'cpo_manager_id');
        }
    }
}
