<?php

namespace App\Observers;

use App\Loan;
use App\Notifications\LoanCreatedNotification;
use App\User;

class LoanObserver
{
    /**
     * Handle the loan "created" event.
     *
     * @param  \App\Loan  $loan
     * @return void
     */
    public function created(Loan $loan)
    {
        $user = auth()->user();

        $loan->approval()->create([
            'line_manager_id' => $user->line_manager->id,
        ]);

        $user->line_manager->notify(new LoanCreatedNotification($loan));
    }

    /**
     * Handle the loan "updated" event.
     *
     * @param  \App\Loan  $loan
     * @return void
     */
    public function updated(Loan $loan)
    {
        //
    }

    /**
     * Handle the loan "deleted" event.
     *
     * @param  \App\Loan  $loan
     * @return void
     */
    public function deleted(Loan $loan)
    {
        //
    }

    /**
     * Handle the loan "restored" event.
     *
     * @param  \App\Loan  $loan
     * @return void
     */
    public function restored(Loan $loan)
    {
        //
    }

    /**
     * Handle the loan "force deleted" event.
     *
     * @param  \App\Loan  $loan
     * @return void
     */
    public function forceDeleted(Loan $loan)
    {
        //
    }
}
