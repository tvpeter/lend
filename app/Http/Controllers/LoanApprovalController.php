<?php

namespace App\Http\Controllers;

use App\LoanApproval;
use App\Permission;
use App\Services\LoanProductService;
use App\Services\StaffLoanService;
use App\User;
use Illuminate\Http\Request;

class LoanApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin') || $user->hasAllPermissions(Permission::all())) {
            $loanApprovals = LoanApproval::latest();
        } else if ($user->can('cpo-approval') && !$user->hasRole('cpo-manager')) {
            $loanApprovals = LoanApproval::where('cpo_manager_id', '!=', null)->latest();
        } else if ($user->can('hr-approval') && !$user->hasRole('hr-manager')) {
            $loanApprovals = LoanApproval::where('hr_manager_id', '!=', null)->latest();
        } else if ($user->can('line-manager-approval') && !$user->hasRole('line-manager')) {
            $loanApprovals = LoanApproval::where('line_manager_id', '!=', null)->latest();
        } else {
            $loanApprovals = $user->approvals()->latest();
        }

        $loanApprovals = $loanApprovals->with('loan', 'loan.user', 'loan.approval')->paginate(1000);

        return view('pages.approvals.index', compact('loanApprovals'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LoanApproval  $loanApproval
     * @return \Illuminate\Http\Response
     */
    public function show(LoanApproval $loanApproval, LoanProductService $loanProductService)
    {
        try {
            $loan = $loanApproval->loan;

            $staff = $loan->user;

            $data = [
                'loan'         => $loan,
                'staff'        => $staff,
                'loanApproval' => $loanApproval,
                'loanProduct'  => $loanProductService->find($loan->loan_product_id),
            ];

            return view('pages.approvals.show', $data);
        } catch (\Exception $e) {
            return back()->with('message', messageResponse('danger', 'An error occurred when getting the loan from mambu. Please try again later.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   Request  $request
     * @param  \App\LoanApproval  $loanApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanApproval $loanApproval, StaffLoanService $staffLoanService)
    {
        if ($request->has('line_manager_approval_status')) {
            $staffLoanService->updateLineManagerStatus($request, $loanApproval);
        }

        if ($request->has('hr_manager_approval_status')) {
            $staffLoanService->updateHRManagerStatus($request, $loanApproval);
        }

        if ($request->has('cpo_manager_approval_status')) {
            return $staffLoanService->updateCPOManagerStatus($request, $loanApproval);
        }

        return back()->with('message', messageResponse('success', 'Loan updated successfully'));
    }
}
