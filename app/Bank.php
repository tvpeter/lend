<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $guarded = [];
    
    public function loan(){
        return $this->belongsTo('App\Loan');
    }
}
