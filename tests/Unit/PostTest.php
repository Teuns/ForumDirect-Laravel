<?php
namespace Tests\Unit;
use App\Thread;
use App\Post;
use App\User;
use App\Role;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_can_create_post() {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();

        $user->roles()->attach($role);

        $data = factory(Post::class)->create()->toArray();

        $thread = factory(Thread::class)->create();

        $response = $this
            ->actingAs($user)
            ->json('POST', route('store_post', $thread->id), $data);

        $response->assertStatus(302);
    }

    public function test_can_update_post() {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();

        $user->roles()->attach($role);

        $thread = factory(Thread::class)->create();

        $post = factory(Post::class)->create();

        $data = [
            'user_id' => 1,
            'thread_id' => 1,
            'subforum_id' => $this->faker->unique()->randomDigit,
            'body' => $this->faker->paragraph
        ];

        $this
            ->actingAs($user)
            ->json('POST', route('update_post', $post->id), $data)
            ->assertStatus(302);
    }

    public function test_cannot_update_post() {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();

        $user->roles()->attach($role);

        $thread = factory(Thread::class)->create();

        $post = factory(Post::class)->create();

        $user->id = 2;

        $data = [
            'user_id' => 1,
            'thread_id' => 1,
            'subforum_id' => $this->faker->unique()->randomDigit,
            'body' => $this->faker->paragraph
        ];

        $this
            ->actingAs($user)
            ->json('POST', route('update_post', $post->id), $data)
            ->assertStatus(403);
    }
}