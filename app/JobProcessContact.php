<?php

namespace App;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class JobProcessContact extends ScopeAwareModel
{
    public const TABLE_NAME = 'job_process_contacts';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected static $publicFields = [
        'id',
        'job_process_id',
        'fullname',
        'email',
        'phone_number',
        'extra_info',
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

    public function jobProcess(): BelongsTo
    {
        return $this->belongsTo(JobProcess::class);
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
                'fullname' => 'bail|required|string',
                'email' => 'bail|email|string',
                'phone_number' => 'bail|string',
                'extra_info' => 'bail|string',
            ]
        );
    }

    public static function getValidatorForEditPayload(array $payload): Validator
    {
        return ValidatorFacade::make(
            $payload,
            [
                'id' => 'bail|required|integer|exists:job_process_contacts,id',
                'job_process_id' => 'bail|required|integer|exists:job_processes,id',
                'fullname' => 'bail|required|string',
                'email' => 'bail|email|string',
                'phone_number' => 'bail|string',
                'extra_info' => 'bail|string',
            ]
        );
    }

    public static function listByProcessId(int $jobProcessId): array
    {
        return static::query()->where(['job_process_id' => $jobProcessId])->get()->toArray();
    }

    public static function createJobProcessContact(int $jobProcessContactId, array $jobProcessContactDataFromRequest): self
    {
        $jobProcessContact = new self($jobProcessContactDataFromRequest);
        $jobProcessContact->jobProcess()->associate($jobProcessContactId);
        $jobProcessContact->save();

        return $jobProcessContact;
    }

    public static function editJobProcessContact(int $id, array $jobProcessContactDataFromRequest): self
    {
        self::query()->where('id', '=', $id)->update($jobProcessContactDataFromRequest);

        return self::query()->findOrFail($id);
    }

    public static function deleteJobProcessContact(int $id): int
    {
        return static::query()->where('id', '=', $id)->update(['deleted_at' => new \DateTime()]);
    }
}
