<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           'name'      => 'Administrator',
           'email'     => 'ltsociallogin@gmail.com',
           'password'  => Illuminate\Support\Facades\Hash::make('Admin123')
        ]);
    }
}
