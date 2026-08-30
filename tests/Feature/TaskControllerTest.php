<?php

// tests/Feature/TaskControllerTest.php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_認証ユーザーはタスク一覧を表示できる(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(2)->create();

        $this->actingAs($user)->get(route('tasks.index'))->assertOk();
    }

    public function test_未ログインユーザーはタスク一覧にアクセスできない(): void
    {
        $this->get(route('tasks.index'))->assertRedirect(route('login'));
    }

    public function test_認証ユーザーはタスクを作成しタグを付けられる(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'レポート作成',
            'description' => null,
            'status' => 'pending',
            'due_date' => null,
            'category_id' => $category->id,
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        $task = Task::where('title', 'レポート作成')->first();
        $this->assertNotNull($task);
        $response->assertRedirect(route('tasks.show', $task));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id,
            'title' => 'レポート作成',
        ]);

        foreach ($tags as $tag) {
            $this->assertDatabaseHas('task_tag', [
                'task_id' => $task->id,
                'tag_id' => $tag->id,
            ]);
        }
    }

    public function test_タイトルがないとタスクを作成できない(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->post(route('tasks.store'), [
            'title' => '',
            'status' => 'pending',
            'category_id' => $category->id,
        ])->assertSessionHasErrors('title');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_タスク詳細を表示できる(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['title' => '詳細テスト']);

        $this->actingAs($user)
            ->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSee('詳細テスト');
    }

    public function test_所有者は自分のタスクを更新できる(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)->put(route('tasks.update', $task), [
            'title' => '更新後タイトル',
            'status' => 'completed',
            'category_id' => $category->id,
        ])->assertRedirect(route('tasks.show', $task));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => '更新後タイトル',
            'status' => 'completed',
        ]);
    }

    public function test_所有者は自分のタスクを削除できる(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
