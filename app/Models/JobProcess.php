<?php

namespace App\Models;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;

class JobProcess extends ScopeAwareModel
{
    public const TABLE_NAME = 'job_processes';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected static $publicFields = [
        'id',
        'user_id',
        'name',
        'active',
        'organization_name',
        'organization_description',
        'job_title',
        'job_description',
        'job_link',
        'job_origin_platform',
        'salary_requested',
        'salary_offered',
        'location_country',
        'location_city',
        'location_extra_info',
        'is_fully_remote',
        'date_start_offered',
        'date_start_requested',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected static $privateFields = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d H:i:s';

    public static function getValidatorForCreatePayload(array $payload): Validator
    {
        return ValidatorFacade::make(
            $payload,
            [
                'name' => 'bail|required|string',
                'organization_name' => 'bail|required|string',
                'organization_description' => 'bail|string',
                'job_title' => 'bail|required|string',
                'job_description' => 'bail|string',
                'job_link' => 'bail|url',
                'job_origin_platform' => 'bail|string',
                'salary_requested' => 'bail|string',
                'salary_offered' => 'bail|string',
                'location_country' => 'bail|required|string|min:2|max:3|alpha',
                'location_city' => 'bail|required|string',
                'location_extra_info' => 'bail|string',
                'is_fully_remote' => 'bail|required|boolean',
                'date_start_offered' => 'bail|date',
                'date_start_requested' => 'bail|date',
            ]
        );
    }

    public static function getValidatorForEditPayload(array $payload): Validator
    {
        return ValidatorFacade::make(
            $payload,
            [
                'name' => 'bail|required|string',
                'organization_name' => 'bail|required|string',
                'organization_description' => 'bail|string',
                'job_title' => 'bail|required|string',
                'job_description' => 'bail|string',
                'job_link' => 'bail|url',
                'job_origin_platform' => 'bail|string',
                'salary_requested' => 'bail|string',
                'salary_offered' => 'bail|string',
                'location_country' => 'bail|required|string|min:2|max:3|alpha',
                'location_city' => 'bail|required|string',
                'location_extra_info' => 'bail|string',
                'is_fully_remote' => 'bail|required|boolean',
                'date_start_offered' => 'bail|date',
                'date_start_requested' => 'bail|date',
            ]
        );
    }

    public static function getValidatorForChangeStatusPayload(array $payload): Validator
    {
        return ValidatorFacade::make(
            $payload,
            [
                'job_process_status_id' => 'bail|required|integer|exists:job_process_statuses,id',
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

    public static function viewJobProcess(int $jobProcessId): self
    {
        return self::query()->findOrFail($jobProcessId)->first();
    }

    /**
     * @param array $jobProcessData
     * @return JobProcess
     * @throws \Throwable
     * @throws ValidationException
     */
    public static function createJobProcess(array $jobProcessData): self
    {
        self::getValidatorForCreatePayload($jobProcessData)->validate();

        $jobProcess = new self($jobProcessData);
        $jobProcess->user_id = Auth::user()->id;
        $jobProcess->date_start_offered = empty($jobProcessData['date_start_offered'])
            ? null : date_create_from_format('Y-m-d', $jobProcessData['date_start_offered']);
        $jobProcess->date_start_requested = $jobProcessData['date_start_requested']
            ? null : date_create_from_format('Y-m-d', $jobProcessData['date_start_requested']);
        $jobProcess->saveOrFail();

        return $jobProcess;
    }

    /**
     * @param int $jobProcessId
     * @param array $jobProcessData
     * @return JobProcess|Model
     * @throws ValidationException
     */
    public static function editJobProcess(int $jobProcessId, array $jobProcessData): self
    {
        self::getValidatorForEditPayload($jobProcessData)->validate();
        self::query()->where('id', '=', $jobProcessId)->update($jobProcessData);

        return self::query()->findOrFail($jobProcessId);
    }

    public static function deleteJobProcess(int $jobProcessId): bool
    {
        return static::query()->where('id', '=', $jobProcessId)->update(['deleted_at' => new \DateTime()]);
    }

    public static function jobProcessBelongsToUser(int $jobProcessId, int $userId): bool
    {
        $jobProcess = self::query()->where(['id' => $jobProcessId])->where(['deleted_at' => null])->firstOrFail();

        return ($jobProcess instanceof self && $jobProcess->user_id === $userId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobProcessContacts(): HasMany
    {
        return $this->hasMany(JobProcessContact::class);
    }

    public function JobProcessLog(): HasMany
    {
        return $this->hasMany(JobProcessLog::class);
    }

    public function JobProcessStatus(): HasOne
    {
        return $this->hasOne(JobProcessStatus::class);
    }
}
