<?php

namespace Tests\Unit;

use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TaskAPITest extends TestCase
{

    public function testTaskFetch()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/task')
            ->assertStatus(200)->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            );
    }

    public function testTaskCreation()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/task', [
            'name' => Str::random(10),
            'order'=>random_int(0,20),
            'user_id'=>Auth::id(),
            'category_id'=> Category::inRandomOrder()->first()->id
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Task Created!'
        ]);
    }

    public function testTaskDeletion()
    {
        $user = \App\User::find(1);

        $task = factory(\App\Task::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/task/{$task->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Task Deleted!'
            ]);
    }
}
