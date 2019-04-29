<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPasswordChangedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @param User|Model $user
     */
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
}
