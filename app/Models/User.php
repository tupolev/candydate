<?php

namespace App\Models;

use App\Events\EmailVerifiedEvent;
use App\Events\UserPasswordChangedEvent;
use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends ScopeAwareModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens, Notifiable;

    public const TABLE_NAME = 'users';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const HASH_ALGORITHM_SHA512 = 'sha512';

    protected $table = self::TABLE_NAME;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected static $publicFields = [
        'id',
        'fullname',
        'username',
        'email',
        'verified',
        'language',
        'active',
        'created_at',
        'updated_at',
    ];

    protected static $privateFields = [
        'role',
        'password',
        'salt',
        'verification_link',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'salt', 'verification_link', 'role_id', 'created_at', 'updated_at', 'deleted_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(array_merge(static::$publicFields, static::$privateFields));
        parent::__construct($attributes);
    }

    public static function getValidatorForCreatePayload(array $payload)
    {
        return Validator::make(
            $payload,
            [
                'username' => 'bail|required|unique:users|string|max:25|min:4|alpha_dash',
                'password' => 'bail|required|string|max:16|min:6',
                'fullname' => 'bail|required|string|max:128|min:4',
                'email' => 'bail|required|string|unique:users,email|email|max:128',
                'language_id' => 'bail|required|integer|exists:languages,id',
            ]
        );
    }

    public static function getValidatorForEditPayload(array $payload)
    {
        return Validator::make(
            $payload,
            [
                'fullname' => 'bail|required|string|max:128|min:4',
                'email' => 'bail|required|string|email|max:128',
                'language_id' => 'bail|required|integer|exists:languages,id',
            ]
        );
    }

    public static function getValidatorForChangePasswordPayload(array $payload)
    {
        return Validator::make(
            $payload,
            [
                'password' => 'bail|required|string|max:16|min:6',
            ]
        );
    }

    /**
     * @param array $userData
     *
     * @return User
     * @throws ValidationException
     * @throws ModelNotFoundException
     *
     * @throws \Exception
     */
    public static function registerUser(array $userData): self
    {
        self::getValidatorForCreatePayload($userData)->validate();

        $user = new self($userData);
        $user->role_id = Role::query()->where(['name' => Role::ROLE_USER])->firstOrFail(['id'])->getAttributeValue('id');
        $user->language_id = Language::query()->where(['id' => $userData['language_id']])->firstOrFail(['id'])->getAttributeValue('id');
        $user->generateRandomSalt();
        $user->generateRandomVerificationLink();
        $user->setCreatedAt(new \DateTime());
        $user->verified = false;
        $user->password = self::encodePassword($userData['password'], $user->salt);
        $user->save();

        $user->notify(new NewUserRegisteredNotification($user));

        return $user;
    }

    public static function completeRegistration(string $username, string $verificationHash): bool
    {
        $user = self::query()->where(['username' => $username, 'verification_link' => $verificationHash])->firstOrFail();
        $user->verified = true;
        $user->active = true;
        $user->setUpdatedAt(new \DateTime());

        $success = $user->save();

        if ($success) {
            Event::dispatch(new EmailVerifiedEvent($user));
        }

        return $success;
    }

    /**
     * @param int $userId
     * @param array $userData
     *
     * @return User|Model
     * @throws ValidationException
     * @throws ModelNotFoundException
     *
     * @throws \Exception
     */
    public static function editUser(int $userId, array $userData): self
    {
        self::getValidatorForEditPayload($userData)->validate();

        self::query()->where('id', '=', $userId)->update([
            'language_id' => $userData['language_id'],
            'fullname' => $userData['fullname'],
            'email' => $userData['email'],
        ]);

        return self::query()->findOrFail($userId);
    }

    /**
     * @param int $userId
     * @param array $userData
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public static function changeUserPassword(int $userId, array $userData)
    {
        self::getValidatorForChangePasswordPayload($userData)->validate();

        $user = self::query()->findOrFail($userId);
        $newEncodedPassord = self::encodePassword($userData['password'], $user->salt);
        if ($newEncodedPassord !== $user->password) {
            $user->update(['password' => $newEncodedPassord, self::UPDATED_AT => new \DateTime()]);

            Event::dispatch(new UserPasswordChangedEvent($user));
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public static function findForPassport($username)
    {
        return static::query()->where(['username' => $username])->first();
    }

    public function validateForPassportPasswordGrant(string $password): bool
    {
        $salt = $this->salt;
        $storedPasswordHash = strtoupper($this->getAuthPassword());
        $computedHashedPassword = strtoupper(self::encodePassword($password, $salt));

        return $storedPasswordHash === $computedHashedPassword;
    }

    private static function encodePassword(string $password, string $salt, string $algorithm = self::HASH_ALGORITHM_SHA512): string
    {
        return hash($algorithm, trim($password) . $salt);
    }

    private function generateRandomSalt(): void
    {
        $this->salt = substr(static::generateRandomHash(), random_int(0, 5), 10);
    }

    private function generateRandomVerificationLink(): void
    {
        $this->verification_link = substr(static::generateRandomHash(), random_int(0, 5), 30);
    }

    private static function generateRandomHash(): string
    {
        return hash(self::HASH_ALGORITHM_SHA512, strftime(DATE_ATOM, time()));
    }

    public function createClientCredentials(): Client
    {
        return app(ClientRepository::class)->createPasswordGrantClient($this->id, $this->username, config('app.url'));
    }
}
