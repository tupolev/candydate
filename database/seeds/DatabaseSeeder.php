<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call('RoleSeeder');
        $this->call(LanguageSeeder::class);
        $this->call(JobProcessStatusSeeder::class);
    }
}
