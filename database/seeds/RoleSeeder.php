<?php

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(Role::TABLE_NAME)->insert([
            'id' => 1,
            'name' => Role::ROLE_SUPER_ADMIN,
            'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', 'now'),
        ]);
        DB::table(Role::TABLE_NAME)->insert([
            'id' => 2,
            'name' => Role::ROLE_USER,
            'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', 'now'),
        ]);
        DB::table(Role::TABLE_NAME)->insert([
            'id' => 3,
            'name' => Role::ROLE_ADMIN,
            'created_at' => DateTime::createFromFormat('Y-m-d H:i:s', 'now'),
        ]);
    }
}
