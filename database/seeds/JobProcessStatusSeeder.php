<?php

use App\Models\JobProcessStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobProcessStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(JobProcessStatus::TABLE_NAME)->insert([
            'id' => 1,
            'name' => 'created',
            'created_at' => new \DateTime(),
        ]);
        DB::table(JobProcessStatus::TABLE_NAME)->insert([
            'id' => 2,
            'name' => 'in progress',
            'created_at' => new \DateTime(),
        ]);
        DB::table(JobProcessStatus::TABLE_NAME)->insert([
            'id' => 3,
            'name' => 'stale',
            'created_at' => new \DateTime(),
        ]);
        DB::table(JobProcessStatus::TABLE_NAME)->insert([
            'id' => 4,
            'name' => 'rejected',
            'created_at' => new \DateTime(),
        ]);
        DB::table(JobProcessStatus::TABLE_NAME)->insert([
            'id' => 5,
            'name' => 'cancelled',
            'created_at' => new \DateTime(),
        ]);
        DB::table(JobProcessStatus::TABLE_NAME)->insert([
            'id' => 5,
            'name' => 'archived',
            'created_at' => new \DateTime(),
        ]);
    }
}
