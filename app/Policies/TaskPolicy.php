<?php

// app/Policies/TaskPolicy.php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    public function view(User $user, Task $task): bool
    {
        // 所有者だけに制限しない → 誰でも閲覧可能にする
        return true;
    }
}
