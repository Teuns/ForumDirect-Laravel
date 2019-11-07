<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $member = Role::create([
            'id' => 1,
            'name' => 'Member', 
            'slug' => 'member',
            'permissions' => [
                'edit-post' => true,
                'create-post' => true,
                'edit-thread' => true,
                'update-post' => true,
                'create-thread' => true,
                'update-thread' => true
            ]
        ]);

        $administrator = Role::create([
            'id' => 2,
            'name' => 'Administrator', 
            'slug' => 'administrator',
            'permissions' => [
                'edit-post' => true,
                'create-post' => true,
                'edit-thread' => true,
                'update-post' => true,
                'publish-post' => true,
                'create-thread' => true,
                'update-thread' => true,
                'publish-thread' => true
            ]
        ]);

        $guest = Role::create([
            'id' => 3,
            'name' => 'Guest', 
            'slug' => 'guest',
            'permissions' => []
        ]);
    }
}