<?php

namespace App;

use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
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
        'role',
        'fullname',
        'username',
        'email',
        'verified',
        'language',
        'active',
    ];

    protected static $privateFields = [
        'password',
        'salt',
        'verification_link',
        'created_at',
        'updated_at',
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
        'password', 'salt', 'verification_link', 'role_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(array_merge(static::$publicFields, static::$privateFields));
        parent::__construct($attributes);
    }

    public static function registerUser(array $userDataFromRequest): self
    {
        $user = new self($userDataFromRequest);
        $user->role_id = Role::query()->where(['name' => Role::ROLE_USER])->firstOrFail(['id'])->getAttributeValue('id');
        $user->language_id = Language::query()->where(['id' => $userDataFromRequest['language_id']])->firstOrFail(['id'])->getAttributeValue('id');
        $user->generateRandomSalt();
        $user->generateRandomVerificationLink();
        $user->setCreatedAt(new \DateTime());
        $user->verified = false;
        $user->password = $user->encodePassword($userDataFromRequest['password'], $user->salt);
        $user->save();

        $user->notify(new NewUserRegisteredNotification($user));

        return $user;
    }

    public static function completeRegistration(string $username, string $verificationHash)
    {
        $user = User::query()->where(['username' => $username, 'verification_link' => $verificationHash])->firstOrFail();
        $user->verified = true;
        $user->active = true;
        $user->setUpdatedAt(new \DateTime());

        return $user->save();
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
        $computedHashedPassword = strtoupper($this->encodePassword($password, $salt));

        return $storedPasswordHash === $computedHashedPassword;
    }

    private function encodePassword(string $password, string $salt, string $algorithm = self::HASH_ALGORITHM_SHA512): string
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

    public function getEmailTag(): string
    {
        return sprintf('%s<%s>', $this->fullname, $this->email);
    }
}
