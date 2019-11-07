<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'ForumDirect',
            'email' => Str::random(10).'@gmail.com',
            'user_avatar' => 'https://ui-avatars.com/api/?name=Forum+Direct',
            'password' => bcrypt('secret'),
            'primary_role' => 1,
        ]);

        $this->call(\RolesSeeder::class);

        DB::table('role_users')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        DB::table('forums')->insert([
            'id' => 1,
            'name' => 'Category 1',
        ]);

        DB::table('subforums')->insert([
            'id' => 1,
            'name' => 'Subcategory 1',
            'slug' => Str::slug('Subcategory 1'),
            'description' => 'A description',
            'forum_id' => 1,
        ]);

        DB::table('threads')->insert([
            'title' => 'Thank you for installing ForumDirect!',
            'slug' => Str::slug('Thank you for installing ForumDirect!'),
            'body' => 'Hello, Thank *you* for choosing our forum system as yours. If it is correctly set up, then it should work without any problems. 
            If you still have any questions or are having any problems, than do not heistate to ask us at our GitHub-page.

Again: Thank you! 

Regards, ForumDirect 
            
**PS**: You can obviously delete this user and thread',
            'user_id' => 1,
            'lastpost_uid' => 1,
            'subforum_id' => 1,
            'published' => 1,
            'lastpost_date' => Carbon\Carbon::now(),
            'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
