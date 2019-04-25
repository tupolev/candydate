<?php

namespace App;

class JobProcess extends ScopeAwareModel
{
    public const TABLE_NAME = 'job_processes';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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
    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d\TH:i:sO';

    public function __construct(array $attributes = [])
    {
        $this->fillable(array_merge(static::$publicFields, static::$privateFields));
        parent::__construct($attributes);
    }

    public static function listByUserId(int $userId): array
    {
        return static::query()->where(['user_id' => $userId])->get()->toArray();
    }

    public function user(): void
    {
        $this->hasOne(User::class);
    }

    public function jobProcessContacts(): void
    {
        $this->hasMany(JobProcessContact::class);
    }

    public function JobProcessLog(): void
    {
        $this->hasMany(JobProcessLog::class);
    }

    public function JobProcessStatus(): void
    {
        $this->hasOne(JobProcessStatus::class);
    }
}
