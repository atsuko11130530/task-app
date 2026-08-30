{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>タスク管理アプリ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 text-center">
        <h1 class="text-3xl font-bold text-indigo-600 mb-2">タスク管理アプリ</h1>
        <p class="text-gray-600 mb-8">タスクを分類・タグ付けし、ランキングで利用状況を整理できます。</p>
        @guest
            <div class="flex gap-4">
                <a href="/login" class="rounded-md bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700">ログイン</a>
                <a href="/register" class="rounded-md border border-gray-300 bg-white px-6 py-2 text-gray-700 hover:bg-gray-50">会員登録</a>
            </div>
        @else
            <p class="mb-4 text-gray-700">{{ Auth::user()->name }} さん、ログイン中です。</p>
            <div class="flex gap-4">
                <a href="{{ route('tasks.index') }}" class="rounded-md bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700">タスク一覧へ</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-md border border-gray-300 bg-white px-6 py-2 text-gray-700 hover:bg-gray-50">ログアウト</button>
                </form>
            </div>
        @endguest
    </div>
</body>
</html>