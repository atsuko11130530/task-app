<?php

// tests/Feature/RankingTest.php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_認証ユーザーはランキングを表示できる(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => '人気タグ']);
        $task = Task::factory()->create();
        $task->tags()->attach($tag);

        $this->actingAs($user)
            ->get(route('ranking.index'))
            ->assertOk()
            ->assertSee('人気タグ');
    }

    public function test_ランキングはタスク数の多い順に並ぶ(): void
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(3)->create();

        $top = Tag::factory()->create(['name' => '最多タグ']);
        $middle = Tag::factory()->create(['name' => '中位タグ']);
        $low = Tag::factory()->create(['name' => '最少タグ']);

        // タスク数を 3 / 2 / 1 にする
        $top->tasks()->attach($tasks->pluck('id')->toArray());
        $middle->tasks()->attach($tasks->take(2)->pluck('id')->toArray());
        $low->tasks()->attach($tasks->take(1)->pluck('id')->toArray());

        $this->actingAs($user)
            ->get(route('ranking.index'))
            ->assertOk()
            ->assertSeeInOrder(['最多タグ', '中位タグ', '最少タグ']);
    }
}
