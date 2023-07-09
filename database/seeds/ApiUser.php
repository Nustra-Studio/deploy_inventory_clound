<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_cabangs')->insert([
            'username' => 'sambo',
            'password' => bcrypt('admin123'),
            'uuid' => '1234567890',
            'role' => 'kasir',
            'cabang_id' => '1',
            'api_key' => '1234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
