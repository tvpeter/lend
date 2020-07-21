<?php

namespace App\Http\Controllers;

use App\Loan;
use App\TopUp;
use Illuminate\Http\Request;

class TopUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'loan_tenure'  => 'required',
            'loan_amount'  => 'required',
            'loan_purpose' => 'required',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        $loanTopUp = collect($loan->toArray())->except('created_at', 'updated_at', 'id', 'user_id', 'mambu_encoded_key', 'mambu_id', 'disbursement_transaction_code', 'status')->merge([
            'loan_amount'       => round($request->loan_amount, 2),
            'loan_tenure'       => $request->loan_tenure,
            'loan_purpose'      => $request->loan_purpose,
            'is_top_up'         => true,
            'top_up_amount'     => round($request->loan_amount - $request->balance_left, 2),
        ])->toArray();

        $topUp = auth()->user()->loans()->create($loanTopUp);

        $loan->topUps()->create(['child_key' => $topUp->id]);

        return redirect()->route('loans.show', $topUp)->with('message', messageResponse('success', 'Loan Top Up created successfully, the status would be communicated to you as approvals are given.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TopUp  $topUp
     * @return \Illuminate\Http\Response
     */
    public function show(TopUp $topUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TopUp  $topUp
     * @return \Illuminate\Http\Response
     */
    public function edit(TopUp $topUp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TopUp  $topUp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TopUp $topUp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TopUp  $topUp
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopUp $topUp)
    {
        //
    }
}
