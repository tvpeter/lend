<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Otp;
use App\Services\MambuLoanService;
use App\Services\OfferLetterService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class OfferLetterController extends Controller
{
    private $offerLetterService, $smsService;

    public function __construct(OfferLetterService $offerLetterService, SmsService $smsService)
    {
        $this->offerLetterService = $offerLetterService;
        $this->smsService         = $smsService;
    }

    /**
     *
     * View offer letter
     *
     * @param Loan $loan
     *
     * @return View
     */
    public function show(Loan $loan)
    {
        try {
            // generate offer letter
            $offerLetter = $this->offerLetterService->generateOfferLetter($loan);

            if (isset($offerLetter['status']) && $offerLetter['status'] != 'success') {
                return back()->with('message', messageResponse('danger', 'An error occurred when generating offer letter'));
            }

            if (!session()->has("$loan->mambu_id-otp-sent")) {
                $otpResponse = $this->resend_otp($loan);

                if (is_array($otpResponse) && isset($otpResponse['status']) && !$otpResponse['status']) {
                    return back()->with('message', messageResponse('danger', $otpResponse['message']));
                }
            }

            $data = [
                'offerLetter' => $offerLetter['data']['html'],
                'loan'        => $loan,
            ];

            return view('pages.offer-letter.show', $data);
        } catch (\Exception $e) {
            return back()->with('message', messageResponse('danger', 'An error occurred when generating offer letter' . $e->getMessage()));
        }
    }

    /**
     * Sign offer letter
     *
     * @param Loan $loan
     * @param Request $request
     * @param MambuLoanService $mambuLoanService
     */
    public function sign(Loan $loan, Request $request, MambuLoanService $mambuLoanService)
    {
        $otp = Otp::where(['loan_id' => $loan->id, 'used' => false, 'code' => $request->otp])->first();

        if (!$otp) {
            return back()->with('message', messageResponse('danger', 'OTP Verification failed, match not found'));
        }

        if (now()->greaterThan($otp->expiry_date)) {
            return back()->with('message', messageResponse('danger', 'OTP Code has expired, please resend a new otp.'));
        }

        $otp->delete();

        return $loan->is_top_up ? $mambuLoanService->close($loan) : $mambuLoanService->approve($loan);
    }

    /**
     * Resend otp
     * @param Loan $loan
     */
    public function resend_otp(Loan $loan)
    {
        $otp = Otp::firstOrCreate(
            [
                'loan_id' => $loan->id,
                'used'    => false,
            ],
            [
                'code'        => generateRandomNumber(6),
                'expiry_date' => now()->addHour(1)->toDateTimeString(),
            ]
        );

        if (now()->greaterThan($otp->expiry_date)) {
            $otp->update([
                'code'        => generateRandomNumber(6),
                'expiry_date' => now()->addHour(1)->toDateTimeString(),
            ]);

            $otp = $otp->refresh();
        }

        $otpSent = $this->smsService->sendSms(sanitizePhoneNumber($loan->mobile_number), "Please use this OTP to sign your offer letter $otp->code");

        if (!$otpSent) {
            return [
                'status'  => false,
                'message' => 'An error occurred when sending an OTP to your registered phone number.',
            ];
        }

        session()->put("$loan->mambu_id-otp-sent", true);

        session()->flash('message', messageResponse('success', "An OTP has been sent to your registered phone number ******" . substr($loan->mobile_number, -5)));

        return back();
    }
}
