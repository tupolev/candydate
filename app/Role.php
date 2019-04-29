<?php

namespace App;

class Role extends ScopeAwareModel
{
    public const TABLE_NAME = 'roles';
    public const ROLE_ADMIN = 'role_admin';
    public const ROLE_USER = 'role_user';
    public const ROLE_SUPER_ADMIN = 'role_super_admin';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d H:i:s';


    protected static $publicFields = [
        'id',
        'name',
    ];

    protected static $privateFields = [
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
