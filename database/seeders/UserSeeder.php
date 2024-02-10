<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            'fname'=>'matin',
            'lname'=>'nejatbakhsh',
            'email'=>'asd@gmail.com',
            'password'=>Hash::make('password')
        ];
        User::create($user);

        User::factory()
           ->count(40)
           ->create();

    }


}
