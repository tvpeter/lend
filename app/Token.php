<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable =[
        'token',
        'loanId',
        'authority',
        'validity',
    ];
}
