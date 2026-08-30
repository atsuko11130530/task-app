<?php

// tests/Feature/Api/V1/TaskApiTest.php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_一覧は_data_と_meta_の構造を返す(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'status', 'due_date', 'category', 'tags', 'tags_count', 'user_id'],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
    }

    public function test_キーワードで絞り込める(): void
    {
        Task::factory()->create(['title' => 'Laravelの学習']);
        Task::factory()->create(['title' => '買い物に行く']);

        $response = $this->getJson('/api/v1/tasks?keyword=Laravel');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Laravelの学習');
    }

    public function test_状態で絞り込める(): void
    {
        Task::factory()->create(['status' => 'completed']);
        Task::factory()->create(['status' => 'pending']);

        $response = $this->getJson('/api/v1/tasks?status=completed');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.status', 'completed');
    }

    public function test_per_page_で件数を指定できる(): void
    {
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/tasks?per_page=2');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('meta.per_page', 2);
        $response->assertJsonPath('meta.total', 5);
    }

    public function test_per_page_が上限を超えると_422(): void
    {
        $response = $this->getJson('/api/v1/tasks?per_page=200');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['per_page']);
    }

    public function test_詳細を取得できる(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $task->id);
    }

    public function test_存在しない_i_dは_404_の_jso_nを返す(): void
    {
        $response = $this->getJson('/api/v1/tasks/99999');

        $response->assertStatus(404);
        $response->assertExactJson(['error' => 'タスクが見つかりませんでした。']);
    }

    public function test_タスクを登録できる(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $payload = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'API登録タスク',
            'description' => '説明',
            'status' => 'pending',
            'due_date' => '2026-07-01',
            'tags' => $tags->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/v1/tasks', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('data.title', 'API登録タスク');

        $task = Task::where('title', 'API登録タスク')->first();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'user_id' => $user->id]);
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('task_tag', ['task_id' => $task->id, 'tag_id' => $tag->id]);
        }
    }

    public function test_不正な入力は_422_を返す(): void
    {
        $response = $this->postJson('/api/v1/tasks', [
            'title' => '',
            'status' => 'done',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['user_id', 'category_id', 'title', 'status']);
    }

    public function test_タスクを更新できる(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $task = Task::factory()->create(['title' => '更新前']);

        $payload = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => '更新後',
            'status' => 'completed',
            'tags' => [],
        ];

        $response = $this->putJson("/api/v1/tasks/{$task->id}", $payload);

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', '更新後');
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => '更新後']);
    }

    public function test_タスクを削除すると_204_を返す(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
