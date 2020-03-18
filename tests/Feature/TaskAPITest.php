<?php

namespace Tests\Unit;

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
        $this->withoutMiddleware();

        $response = $this->json('POST', '/api/task', [
            'name' => Str::random(10),
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Task Created'
        ]);
    }

    public function testTaskDeletion()
    {
        $user = \App\User::find(1);

        $task = \App\Task::create(['name' => 'Some random task']);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/task/{$task->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Task Deleted'
            ]);
    }
}
