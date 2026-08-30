{{-- resources/views/tasks/show.blade.php --}}
@extends('layouts.app')
@section('title', $task->title)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold">{{ $task->title }}</h1>

        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-24 text-gray-500">カテゴリ</dt><dd>{{ $task->category->name }}（全 {{ $task->category->tasks_count }} 件）</dd></div>
            <div class="flex"><dt class="w-24 text-gray-500">状態</dt><dd>{{ ['pending' => '未着手', 'in_progress' => '進行中', 'completed' => '完了'][$task->status] }}</dd></div>
            <div class="flex"><dt class="w-24 text-gray-500">期限</dt><dd>{{ $task->due_date?->format('Y/m/d') ?? '未設定' }}</dd></div>
            <div class="flex"><dt class="w-24 text-gray-500">登録者</dt><dd>{{ $task->user->name }}</dd></div>
            <div class="flex">
                <dt class="w-24 text-gray-500">タグ</dt>
                <dd class="flex flex-wrap gap-1">
                    @forelse ($task->tags as $tag)
                        <span class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $tag->name }}</span>
                    @empty
                        <span class="text-gray-400">なし</span>
                    @endforelse
                </dd>
            </div>
        </dl>

        @if ($task->description)
            <div class="mt-4">
                <h2 class="mb-1 text-sm text-gray-500">説明</h2>
                <p class="whitespace-pre-line text-gray-800">{{ $task->description }}</p>
            </div>
        @endif

           {{-- resources/views/tasks/show.blade.php の編集・削除ボタン --}}
        <div class="mt-6 flex gap-3">
            @can('update', $task)
                <a href="{{ route('tasks.edit', $task) }}" class="rounded-md bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">編集</a>
            @endcan
            @can('delete', $task)
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md bg-red-500 px-4 py-2 text-white hover:bg-red-600">削除</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:underline">← 一覧に戻る</a>
    </div>
</div>
@endsection