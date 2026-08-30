{{-- resources/views/ranking/index.blade.php --}}
@extends('layouts.app')
@section('title', 'ランキング')

@section('content')
<h1 class="text-2xl font-bold mb-6">人気のタグ</h1>

@if ($rankedTags->isEmpty())
    <p class="text-gray-500">まだタグの付いたタスクがありません。</p>
@else
    <ol class="space-y-3">
        @foreach ($rankedTags as $index => $tag)
            <li class="flex items-center justify-between rounded-lg bg-white p-4 shadow">
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center text-xl font-bold text-indigo-600">{{ $index + 1 }}</span>
                    <span class="font-semibold text-gray-800">{{ $tag->name }}</span>
                </div>
                <span class="text-sm text-gray-700">{{ $tag->tasks_count }} 件のタスク</span>
            </li>
        @endforeach
    </ol>
@endif
@endsection