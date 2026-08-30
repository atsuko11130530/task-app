{{-- resources/views/tasks/index.blade.php --}}
@extends('layouts.app')
@section('title', 'タスク一覧')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">タスク一覧</h1>
    <a href="{{ route('tasks.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">タスクを登録</a>
</div>

@if ($tasks->isEmpty())
    <p class="text-gray-500">タスクがまだありません。</p>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">タイトル</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">カテゴリ</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">状態</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">期限</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($tasks as $task)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:underline">{{ $task->title }}</a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $task->category->name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-block rounded bg-gray-100 px-2 py-1 text-xs text-gray-700">
                                {{ ['pending' => '未着手', 'in_progress' => '進行中', 'completed' => '完了'][$task->status] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $task->due_date?->format('Y/m/d') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
@endif
@endsection