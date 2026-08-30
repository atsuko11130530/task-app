<?php

// tests/Feature/TaskPolicyTest.php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_更新と削除は所有者だけが許可される(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->assertTrue($owner->can('update', $task));
        $this->assertTrue($owner->can('delete', $task));
        $this->assertFalse($other->can('update', $task));
        $this->assertFalse($other->can('delete', $task));
    }

    public function test_他人はタスクの編集画面を開けない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('tasks.edit', $task))
            ->assertForbidden();
    }

    public function test_他人はタスクを削除できない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($other)
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
