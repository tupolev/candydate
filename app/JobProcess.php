<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProcess extends Model
{
    public const TABLE_NAME = 'job_processes';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d\TH:i:sO';

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
