{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')
@section('title', 'カテゴリ')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">カテゴリ</h1>
    <a href="{{ route('categories.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">カテゴリを追加</a>
</div>

@if ($categories->isEmpty())
    <p class="text-gray-500">カテゴリがまだありません。</p>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">カテゴリ名</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">タスク数</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($categories as $category)
                    <tr>
                        <td class="px-4 py-3">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $category->tasks_count }} 件</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:underline">編集</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="ml-2 inline" onsubmit="return confirm('削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection