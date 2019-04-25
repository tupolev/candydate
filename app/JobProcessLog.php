<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProcessLog extends Model
{
    public const TABLE_NAME = 'job_process_log';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d\TH:i:sO';

    public function jobProcess(): void
    {
        $this->hasOne(JobProcess::class);
    }

    public function jobProcessStatus(): void
    {
        $this->hasOne(JobProcessStatus::class);
    }
}
