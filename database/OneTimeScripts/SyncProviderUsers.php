<?php

use Illuminate\Database\Seeder;

class SyncProviderUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = \App\User::all();
        // dd($users[0]->password);
        foreach ($users as $key => $user) {
            if(!is_null($user->password) && !empty($user->password)) {
                $user->providers()->sync([1], false);
            }
        }
    }
}