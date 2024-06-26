<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('roles')->insert([
            'id'=>1,
            'name'=>'admin'
        ]);
        DB::table('roles')->insert([
            'id'=>2,
            'name'=>'data_entry'
        ]);
        DB::table('roles')->insert([
            'id'=>3,
            'name'=>'client'
        ]);
        DB::table('users')->insert([
            'id'=>1,
            'name'=>'admin',
            'role_id'=>1,
            'password'=>bcrypt("12345678"),
            'email'=>'admin@admin.com',
            'email_verified_at'=>now(),
        ]);


    }
}
