<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopUp extends Model
{
    protected $guarded = [];
    
    public function parent_loan(){
        return $this->belongsTo('App\Loan', 'parent_key', 'id');
    }
    
    public function loan(){
        return $this->belongsTo('App\Loan', 'child_key', 'id');
    }
}
