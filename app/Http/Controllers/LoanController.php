<?php

namespace App\Http\Controllers;

use App\Actions\CreateLoanAction;
use App\Http\Requests\CreateLoanRequest;
use App\Loan;
use App\Services\BambooService;
use App\Services\MambuLoanService;
use App\Services\StaffLoanService;
use App\User;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->hasRole('superadmin')) {
            $loans = Loan::latest();
        } else {
            $loans = auth()->user()->loans()->latest();
        }

        if ($request->has('status')) {
            $loans = $loans->where('status', $request->status);
        }

        $loans = $loans->with('user', 'approval')->paginate(1000);

        return view('pages.loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StaffLoanService $staffLoanService, BambooService $bambooService)
    {
        $user = auth()->user();
        
        if (!$user->canCreateLoan()) {
            return back()->with('message', messageResponse('danger', 'You can only request a loan 3 times in a year.'));
        }

        if (url()->previous() != route('loans.create')) {
            session()->remove('create-loan');
        }

        if (!session()->has('create-loan')) {
            
            if(!$user->bamboo_id){
                return back()->with('message', messageResponse('danger', 'You are not a user on bamboo. Please contact IT Support.'));
            }
            
            if(!$user->line_manager_id){
                return back()->with('message', messageResponse('danger', 'Your line manager was not found on bamboo. Please contact IT Support.'));
            }

            $previousLoan = auth()->user()->loans()->latest()->first();

            if ($previousLoan && !$previousLoan->is_old_loan) {
                session()->put('create-loan', [
                    'step'              => '5',
                    'bvn'               => $previousLoan->bvn,
                    'first_name'        => $previousLoan->first_name,
                    'middle_name'       => $previousLoan->middle_name,
                    'last_name'         => $previousLoan->last_name,
                    'date_of_birth'     => $previousLoan->date_of_birth,
                    'email'             => $previousLoan->email,
                    'work_email'        => $previousLoan->work_email,
                    'gender'            => $previousLoan->gender,
                    'mobile_number'     => $previousLoan->mobile_number,
                    'address'           => $previousLoan->address,
                    'nok_first_name'    => $previousLoan->nok_first_name,
                    'nok_middle_name'   => $previousLoan->nok_middle_name,
                    'nok_last_name'     => $previousLoan->nok_last_name,
                    'nok_mobile_number' => $previousLoan->nok_mobile_number,
                    'nok_relationship'  => $previousLoan->nok_relationship,
                    'bank_id'           => $previousLoan->bank_id,
                    'account_number'    => $previousLoan->account_number,
                ]);
            }
        }
        
        $data = $staffLoanService->loanFormData();

        return view('pages.loans.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateLoanRequest  $request
     * @param StaffLoanService $staffLoanService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLoanRequest $request, StaffLoanService $staffLoanService)
    {
        $step = session('create-loan.step');

        if ($request->has('previous')) {

            session()->put('create-loan', array_merge(session('create-loan'), ['step' => $step - 1]));

            return back();
        }

        if (!$step || $step == 1) {
            return $staffLoanService->registrationStepOne($request);
        }

        if ($step == 2) {
            return $staffLoanService->registrationStepTwo($request);
        }

        if ($step == 3) {
            return $staffLoanService->registrationStepThree($request);
        }

        if ($step == 4) {
            return $staffLoanService->registrationStepFour($request);
        }

        if ($step == 5) {
            return $staffLoanService->registrationStepFive($request);
        }

        return CreateLoanAction::execute($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($loan, MambuLoanService $mambuLoanService)
    {
        try {
            if (auth()->user()->can('report')) {
                $loan = Loan::with('approval')->findOrFail($loan);
            } else {
                $loan = auth()->user()->loans()->with('approval')->findOrFail($loan);
            }

            $data = [
                'loan' => $loan,
            ];

            if ($loan->mambu_id) {
                foreach ($mambuLoanService->fullLoanDetails($loan->mambu_id, $loan) as $key => $item) {
                    $data[$key] = $item;
                }
            }

            return view('pages.loans.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('loans.index')->with('message', messageResponse('danger', 'Error fetching loan from mambu. Please try again.'));
        }
    }
}
