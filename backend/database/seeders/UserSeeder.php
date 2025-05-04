<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name'      => 'Requestor',
            'username'  => 'requestor',
            'email'     => 'requestor@gmail.com',
            'password'  =>  app('hash')->make('Password1234'),
            'avatar'    => null,
            'role'      => 1,
            'is_active' => true

        ]);

        User::create([
            'name'      => 'Worker',
            'username'  => 'worker',
            'email'     => 'worker@gmail.com',
            'password'  =>  app('hash')->make('Password1234'),
            'avatar'    => null,
            'role'      => 2,
            'is_active' => true

        ]);
        
    }
}
