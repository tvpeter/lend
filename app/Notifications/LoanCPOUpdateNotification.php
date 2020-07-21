<?php

namespace App\Notifications;

use App\LoanApproval;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanCPOUpdateNotification extends Notification
{
    use Queueable;

    private $cpoManager, $loanApproval;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $cpoManager, LoanApproval $loanApproval)
    {
        $this->cpoManager    = $cpoManager;
        $this->loanApproval = $loanApproval;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $update = "A loan application for {$this->loanApproval->loan->user->name} was approved by the HR Manager.";

        return (new MailMessage)
            ->subject('Loan Application Update')
            ->view('emails.loan_email', [
                'btnTitle' => 'View Application',
                'route'    => route('loan-approvals.show', $this->loanApproval),
                'name'     => $this->cpoManager->name,
                'update'   => $update,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'link'    => route('loan-approvals.show', $this->loanApproval),
            'message' => 'A new loan has been created for your approval.',
        ];
    }
}
