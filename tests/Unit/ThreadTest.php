<?php
namespace Tests\Unit;
use App\Thread;
use App\User;
use App\Role;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    public function test_can_create_thread() {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();

        $user->roles()->attach($role);

        $data = factory(Thread::class)->create()->toArray();

        $response = $this
            ->actingAs($user)
            ->json('POST', route('store_thread', $this->faker->unique()->randomDigit), $data);

        $response->assertStatus(302);
    }

    public function test_can_update_thread() {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();

        $user->roles()->attach($role);

        $thread = factory(Thread::class)->create();
        $data = [
            'subforum_id' => 1,
            'lastpost_uid' => 1,
            'votes' => 0,
            'views' => 0,
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->sentence,
            'body' => $this->faker->paragraph
        ];

        $this
            ->actingAs($user)
            ->json('POST', route('update_thread', $thread->id), $data)
            ->assertStatus(302);
    }

    public function test_cannot_update_thread() {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();

        $user->roles()->attach($role);

        $thread = factory(Thread::class)->create();

        $user->id = 2;
        
        $data = [
            'subforum_id' => 1,
            'lastpost_uid' => 1,
            'votes' => 0,
            'views' => 0,
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->sentence,
            'body' => $this->faker->paragraph
        ];

        $this
            ->actingAs($user)
            ->json('POST', route('update_thread', $thread->id), $data)
            ->assertStatus(403);
    }
}