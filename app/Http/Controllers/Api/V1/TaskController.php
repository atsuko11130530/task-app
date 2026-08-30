<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(IndexTaskRequest $request)
    {
        $query = Task::with(['category', 'tags'])
            ->withCount('tags');

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $perPage = (int) $request->input('per_page', 20);
        $tasks = $query->latest()->paginate($perPage);

        return TaskResource::collection($tasks);

    }

    public function show(Task $task)
    {
        $task->load(['category', 'tags']);
        $task->loadCount('tags');

        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([
            'user_id' => $validated['user_id'],
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $task->tags()->sync($validated['tags'] ?? []);

        $task->load(['category', 'tags']);
        $task->loadCount('tags');

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $task->tags()->sync($validated['tags'] ?? []);

        $task->load(['category', 'tags']);
        $task->loadCount('tags');

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
