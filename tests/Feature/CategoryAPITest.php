<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CategoryAPITest extends TestCase
{

    public function testCategoryFetch()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/category')
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

    public function testCategoryCreation()
    {
        $this->withoutMiddleware();

        $response = $this->json('POST', '/api/category', [
            'name' => Str::random(10),
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Category Created'
        ]);
    }

    public function testCategoryDeletion()
    {
        $user = \App\User::find(1);

        $category = \App\Category::create(['name' => 'To be deleted']);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/category/{$category->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Category Deleted'
            ]);
    }
}
