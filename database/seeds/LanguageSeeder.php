<?php

use App\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(Language::TABLE_NAME)->insert([
            'id' => 1,
            'name' => 'English',
            'locale' => 'en',
            'active' => true,
            'created_at' => DateTime::createFromFormat('Y-m-d\TH:i:sO', 'now'),
        ]);
        DB::table(Language::TABLE_NAME)->insert([
            'id' => 2,
            'name' => 'Deutsch',
            'locale' => 'de',
            'active' => true,
            'created_at' => DateTime::createFromFormat('Y-m-d\TH:i:sO', 'now'),
        ]);
        DB::table(Language::TABLE_NAME)->insert([
            'id' => 3,
            'name' => 'Español',
            'locale' => 'es',
            'active' => true,
            'created_at' => DateTime::createFromFormat('Y-m-d\TH:i:sO', 'now'),
        ]);

    }
}
