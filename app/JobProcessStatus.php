<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProcessStatus extends Model
{
    public const TABLE_NAME = 'job_process_statuses';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = self::TABLE_NAME;
    protected $dateFormat = 'Y-m-d\TH:i:sO';

    public function jobProcessLogs(): void
    {
        $this->hasMany(JobProcessLog::class);
    }

    public function jobProcesses(): void
    {
        $this->hasMany(JobProcess::class);
    }

}
