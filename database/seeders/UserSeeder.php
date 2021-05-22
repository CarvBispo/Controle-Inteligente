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
        $users = collect([
            '0' => ['Bruce Wayne', 'batman@gmail.com', '123'],
        ]);

        foreach ($users as $user) {
            $this->save($user);
        }
    }

    /**
     * save do seeder
     *
     * @param [type] $newUser
     * @return void
     */
    public function save($newUser)
    {

        $user = new User();
        $user->name = $newUser[0];
        $user->email = $newUser[1];
        $user->password = bcrypt($newUser[2]);
        $user->save();

        $user->roles()->attach([1]);
    }
}
