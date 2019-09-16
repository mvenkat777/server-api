<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;

class UserGroupsRolesTableSeeder extends seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Groups Master table insertion
        DB::table('groups')->delete();
        $groupNames = ['Marketing','Tech team','Sourcing'];
        foreach ($groupNames as $name) {
            $group = new \App\Group();
            $group->name = $name;
            $group->description = $faker->sentence;
            $group->owner_email = $faker->email;
            $group->save();

            $groupIds[] = $group->id;
        }

        // Roles Master table insertion
        DB::table('roles')->delete();
        $roleNames = [
            'Sr Sales Executive','Sales Manager','Sr Engineer','Engg Manager',
            'Sourcing Executive','Sourcing Manager',
        ];

        foreach ($roleNames as $name) {
            $role = new \App\Role();
            $role->name = $name;
            $role->description = $faker->sentence;
            $role->save();

            $roleIds[] = $role->id;
        }

        $count = 100;
        //DB::table('users')->orderBy('created_at', 'desc')->take($count)->delete();
        $user = new User();

        $password = \Hash::make('se12345');
        foreach (range(1, $count) as $index) {
            $user = User::create(array(
                'id' => $faker->uuid,
                'display_name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
                'is_active' => 1,
            ));

            $single_user = User::find($user->id);
            $r = array_rand($roleIds);
            $perm = ['admin','can_read','can_edit'];
            $p = array_rand($perm);
            $single_user->roles()->attach(
                $roleIds[$r],
                ['permission' => $perm[$p]]
            );

            $g = array_rand($groupIds);
            $single_user->groups()->attach($groupIds[$g]);
        }
    }
}
