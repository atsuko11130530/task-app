{{-- resources/views/categories/create.blade.php --}}
@extends('layouts.app')
@section('title', 'カテゴリを追加')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-6">カテゴリを追加</h1>

    <form method="POST" action="{{ route('categories.store') }}" class="space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">カテゴリ名 <span class="text-red-500">*</span></label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('categories.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">キャンセル</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">追加する</button>
        </div>
    </form>
</div>
@endsection