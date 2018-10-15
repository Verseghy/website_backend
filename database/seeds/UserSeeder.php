<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        (new User([
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => Hash::make('test'),
        ]))->save();
    }
}
