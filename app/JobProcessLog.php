<?php

namespace App;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class JobProcessLog extends ScopeAwareModel
{
    public const TABLE_NAME = 'job_process_log';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected static $publicFields = [
        'id',
        'job_process_id',
        'job_process_status_id',
        'title',
        'details',
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

    public function jobProcess()
    {
        return $this->belongsTo(JobProcess::class);
    }

    public function jobProcessStatus()
    {
        return $this->hasOne(JobProcessStatus::class);
    }

    public function __construct(array $attributes = [])
    {
        $this->fillable(array_merge(static::$publicFields, static::$privateFields));
        parent::__construct($attributes);
    }

    public static function getValidatorForCreatePayload(array $payload): Validator
    {
        return ValidatorFacade::make(
            $payload,
            [
                'job_process_id' => 'bail|required|integer|exists:job_processes,id',
                'job_process_status_id' => 'bail|required|integer|exists:job_process_statuses,id',
                'title' => 'bail|required|string',
                'details' => 'bail|string',
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

    public static function listByJobProcessLogId(int $jobProcessLogId): array
    {
        return static::query()->findOrFail($jobProcessLogId)->toArray();
    }

    public static function listByJobProcessId(int $jobProcessId): array
    {
        return static::query()->where(['job_process_id' => $jobProcessId])->get()->toArray();
    }

    public static function createJobProcessLogEntry(array $jobProcessLogData): self
    {
        $jobProcessLog = new self($jobProcessLogData);
        $jobProcessLog->jobProcess()->associate($jobProcessLog->job_process_id);
        $jobProcessLog->save();

        return $jobProcessLog;
    }

    public static function changeJobProcessStatus(int $jobProcessId, int $jobProcessStatusId): bool
    {
        $created = self::createJobProcessLogEntry([
            'job_process_id' => $jobProcessId,
            'job_process_status_id' => $jobProcessStatusId,
            'title' => 'Status changed',
            'details' => 'Status changed',
        ]);

        return $created instanceof self && is_numeric($created->id);
    }
}
