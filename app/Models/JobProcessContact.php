<?php

namespace App\Models;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;

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
                'fullname' => 'bail|required|string',
                'email' => 'bail|email|string',
                'phone_number' => 'bail|string',
                'extra_info' => 'bail|string',
            ]
        );
    }

    public static function listByProcessId(int $jobProcessId): array
    {
        return static::query()->where(['job_process_id' => $jobProcessId])->where(['deleted_at' => null])->get()->toArray();
    }

    public static function viewJobProcessContact(int $jobProcessContactId): array
    {
        return self::query()->find($jobProcessContactId)->first()->toPublicList();
    }

    /**
     * @param int $jobProcessContactId
     * @param array $jobProcessContactData
     * @return JobProcessContact|Model
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public static function createJobProcessContact(int $jobProcessContactId, array $jobProcessContactData): self
    {
        self::getValidatorForCreatePayload($jobProcessContactData)->validate();

        $jobProcessContact = new self($jobProcessContactData);
        $jobProcessContact->jobProcess()->associate($jobProcessContactId);
        $jobProcessContact->save();

        return $jobProcessContact;
    }

    /**
     * @param int $id
     * @param int $jobProcessId
     * @param array $jobProcessContactData
     * @return JobProcessContact|Model
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public static function editJobProcessContact(int $id, int $jobProcessId, array $jobProcessContactData): self
    {
        self::getValidatorForEditPayload($jobProcessContactData)->validate();

        self::query()
            ->where('id', '=', $id)
            ->where('job_process_id', '=', $jobProcessId)
            ->update($jobProcessContactData);

        return self::query()->findOrFail($id);
    }

    public static function deleteJobProcessContact(int $id): void
    {
        static::query()->where('id', '=', $id)->update(['deleted_at' => new \DateTime()]);
    }

    public static function jobProcessContactBelongsToJobProcess(int $jobProcessId, int $jobProcessContactId): bool
    {
        $jobProcessContact = self::query()
            ->where('id', '=', $jobProcessContactId)
            ->where('job_process_id', '=', $jobProcessId)
            ->where(['deleted_at' => null])
            ->first();

        return ($jobProcessContact instanceof self && $jobProcessContact->id === $jobProcessContactId);
    }
}
