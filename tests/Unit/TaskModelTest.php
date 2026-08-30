<?php

// tests/Unit/TaskModelTest.php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_タスクのリレーションが定義されている(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $task = Task::factory()->for($user)->for($category)->create();
        $task->tags()->attach($tag);

        $this->assertTrue($task->user->is($user));
        $this->assertTrue($task->category->is($category));
        $this->assertTrue($task->tags->contains($tag));
    }
}
