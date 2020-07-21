<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanApproval extends Model
{   
    protected $guarded = [];

    public function line_manager()
    {
        return $this->belongsTo('App\User');
    }

    public function hr_manager()
    {
        return $this->belongsTo('App\User');
    }
    
    public function cpo_manager()
    {
        return $this->belongsTo('App\User');
    }

    public function loan()
    {
        return $this->belongsTo('App\Loan');
    }
}
