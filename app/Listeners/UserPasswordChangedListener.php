<?php

namespace App\Listeners;

use App\Events\UserPasswordChangedEvent;
use App\User;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Token;

class UserPasswordChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserPasswordChangedEvent $event
     * @return void
     */
    public function handle(UserPasswordChangedEvent $event)
    {
        //invalidate tokens
        $user = $event->getUser();
        /** @var User $user */
//        $user = User::query()->find($userId);
        $user->tokens()->each(static function ($token) use ($user) {
            /** @var $token Token */
            $token->revoke();
            Log::info(sprintf('Revoked token %s for user %s', $token->id, $user->id));
        });
    }
}
