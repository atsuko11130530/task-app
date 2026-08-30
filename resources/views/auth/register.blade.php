{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', '会員登録')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-6">会員登録</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">名前</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
            <input id="password" type="password" name="password" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">パスワード（確認）</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">登録する</button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        すでにアカウントをお持ちの方は
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">ログイン</a>
    </p>
</div>
@endsection