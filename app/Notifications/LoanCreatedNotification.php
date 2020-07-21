<?php

namespace App\Notifications;

use App\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanCreatedNotification extends Notification
{
    use Queueable;

    private $loan;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
            ->subject('New Staff Loan')
            ->view('emails.loan_created', [
                'line_manager' => $notifiable,
                'loan'         => $this->loan,
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
            'link'    => route('loan-approvals.show', $this->loan->approval),
            'message' => 'A new loan has been created for your approval.',
        ];
    }
}
