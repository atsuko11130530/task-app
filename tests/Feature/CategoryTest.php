<?php

// tests/Feature/CategoryTest.php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_認証ユーザーはカテゴリ一覧を表示できる(): void
    {
        $user = User::factory()->create();
        Category::factory()->count(3)->create();

        $this->actingAs($user)->get(route('categories.index'))->assertOk();
    }

    public function test_認証ユーザーはカテゴリを作成できる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('categories.store'), ['name' => '新しいカテゴリ'])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => '新しいカテゴリ']);
    }

    public function test_カテゴリ名は重複できない(): void
    {
        $user = User::factory()->create();
        Category::factory()->create(['name' => '仕事']);

        $this->actingAs($user)
            ->post(route('categories.store'), ['name' => '仕事'])
            ->assertSessionHasErrors('name');
    }

    public function test_タスクが紐づくカテゴリは削除できない(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Task::factory()->for($category)->create();

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('error', 'このカテゴリにはタスクが紐づいているため削除できません。');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_タスクが紐づかないカテゴリは削除できる(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
