<?php

namespace App\Actions;

use App\Http\Requests\CreateLoanRequest;
use App\Services\BambooService;
use App\User;

class CreateLoanAction
{
    /**
     * Execute the action to create a loan
     *
     * @param CreateLoanRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function execute(CreateLoanRequest $request)
    {   
        $request->merge(session('create-loan'));

        $loan = auth()->user()->loans()->create($request->except([
            'undertaking', 'line_manager_id', 'step',
        ]));
        
        //the notifications happen in LoanObserver.php
        
        session()->remove('create-loan');
        
        return redirect()->route('loans.show', $loan)->with('message', messageResponse('success', 'Loan created successfully, the status would be communicated to you as approvals are given.'));
        
    }
}
