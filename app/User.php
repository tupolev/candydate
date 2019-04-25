<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;

class User extends ScopeAwareModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens;

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
    protected $fillable = [
        'name', 'email', 'fullname', 'password', 'language_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'salt', 'verification_link', 'role_id', 'created_at', 'updated_at', 'deleted_at'
    ];

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

    public function encodePassword(string $password, string $salt, string $algorithm = self::HASH_ALGORITHM_SHA512): string
    {
        return hash($algorithm, trim($password) . $salt);
    }
}
