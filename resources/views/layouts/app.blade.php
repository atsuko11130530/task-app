{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'タスク管理アプリ')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-bold text-indigo-600">タスク管理アプリ</a>
            <div class="flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ route('tasks.index') }}" class="text-gray-700 hover:text-indigo-600">タスク一覧</a>
                    <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-indigo-600">カテゴリ</a>
                    <a href="{{ route('ranking.index') }}" class="text-gray-700 hover:text-indigo-600">ランキング</a>
                    <span class="text-gray-500">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-indigo-600">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">ログイン</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-indigo-600">会員登録</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-md border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>