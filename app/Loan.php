<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];

    /**
     * Check if loan has all approvals give
     *
     * @return boolean
     */
    public function fullApproval()
    {
        return $this->approval ? $this->approval->line_manager_approval_status == 'approved' && $this->approval->hr_manager_approval_status == 'approved' && $this->approval->cpo_manager_approval_status == 'approved' : false;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

    public function approval()
    {
        return $this->hasOne('App\LoanApproval');
    }

    public function topUps()
    {
        return $this->hasMany('App\TopUp', 'parent_key');
    }

    public function topUp()
    {
        return $this->hasOne('App\TopUp', 'child_key');
    }

    public function balanceLeft($repayments)
    {
        $repayments = $repayments->where('state', '!=', 'Paid');

        return $repayments->sum(function($repayment){
            return ($repayment->principal_due + $repayment->interest_due + $repayment->fees_due + $repayment->penalty_due) - ($repayment->principal_paid + $repayment->interest_paid + $repayment->fees_paid + $repayment->penalty_paid);
        });
    }
}
