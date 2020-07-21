<?php

namespace App\Notifications;

use App\LoanApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApplicationUpdateNotification extends Notification
{
    use Queueable;

    private $loanApproval, $update;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(LoanApproval $loanApproval, $update)
    {
        $this->loanApproval = $loanApproval;
        $this->update       = $update;
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
        return (new MailMessage)
            ->subject('Loan Application Update')
            ->view('emails.loan_email', [
                'btnTitle' => 'View Application',
                'route'    => route('loans.show', $this->loanApproval->loan),
                'name'     => $this->loanApproval->loan->user->name,
                'update'   => $this->update,
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
            'link'    => route('loans.show', $this->loanApproval->loan),
            'message' => 'There is an update on your loan application.',
        ];
    }
}
