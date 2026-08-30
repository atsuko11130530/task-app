{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title', 'ログイン')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-6">ログイン</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
            <input id="password" type="password" name="password" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember"
                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember" class="ml-2 text-sm text-gray-600">ログイン状態を保持する</label>
        </div>
        <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">ログイン</button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        アカウントをお持ちでない方は
        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">会員登録</a>
    </p>
</div>
@endsection