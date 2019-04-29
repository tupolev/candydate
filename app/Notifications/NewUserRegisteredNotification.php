<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get the notification’s delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify your account, please.')
            ->markdown('emails.RegisterConfirmationEmail', [
                'user' => $this->user,
                'verificationLink' => $this->getVerificationLink($this->user->username, $this->user->verification_link),
            ]);
    }

    private function getVerificationLink(string $username, string $verificationHash): string
    {
        return route('emailVerification', [
            'username' => $username,
            'verification_hash' => $verificationHash,
        ]);
    }
}
