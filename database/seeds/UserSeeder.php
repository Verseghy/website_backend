<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = config('backpack.base.user_model_fqn');
        (new $user([
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => Hash::make('test'),
        ]))->save();
    }
}
