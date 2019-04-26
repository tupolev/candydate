<?php

namespace App;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

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

    public static function getValidatorForCreatePayload(array $payload): Validator
    {
        return ValidatorFacade::make(
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

    public function __construct(array $attributes = [])
    {
        $this->fillable(array_merge(static::$publicFields, static::$privateFields));
        parent::__construct($attributes);
    }

    public static function listByUserId(int $userId): array
    {
        return static::query()->where(['user_id' => $userId])->get()->toArray();
    }

    public static function createJobProcess(array $jobProcessDataFromRequest): self
    {
        $jobProcess = new self($jobProcessDataFromRequest);
//        $user->role_id = Role::query()->where(['name' => Role::ROLE_USER])->firstOrFail(['id'])->getAttributeValue('id');
//        $user->language_id = Language::query()->where(['id' => $userDataFromRequest['language_id']])->firstOrFail(['id'])->getAttributeValue('id');

        $jobProcess->save();

        return $jobProcess;
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
