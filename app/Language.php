<?php

namespace App;


class Language extends ScopeAwareModel
{
    public const TABLE_NAME = 'languages';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected static $publicFields = [
        'id',
        'name',
        'locale',
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
