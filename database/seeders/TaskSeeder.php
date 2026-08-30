<?php

// database/seeders/TaskSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('email');
        $categories = Category::all()->keyBy('name');
        $tags = Tag::all()->keyBy('name');

        $tasks = [
            ['email' => 'yamada@example.com', 'category' => '仕事', 'title' => '企画書を作成する', 'status' => 'in_progress', 'due_date' => '2026-07-01', 'tags' => ['重要', '今週中']],
            ['email' => 'yamada@example.com', 'category' => '仕事', 'title' => '取引先にメールを返信する', 'status' => 'pending', 'due_date' => '2026-06-20', 'tags' => ['緊急']],
            ['email' => 'yamada@example.com', 'category' => '学習', 'title' => 'Laravel の認可を復習する', 'status' => 'pending', 'due_date' => null, 'tags' => ['アイデア']],
            ['email' => 'suzuki@example.com', 'category' => 'プライベート', 'title' => '友人と旅行の計画を立てる', 'status' => 'pending', 'due_date' => '2026-08-10', 'tags' => []],
            ['email' => 'suzuki@example.com', 'category' => '買い物', 'title' => '日用品を買い足す', 'status' => 'completed', 'due_date' => '2026-06-12', 'tags' => ['今週中']],
            ['email' => 'suzuki@example.com', 'category' => '健康', 'title' => 'ランニングを習慣にする', 'status' => 'in_progress', 'due_date' => null, 'tags' => ['重要']],
            ['email' => 'tanaka@example.com', 'category' => '仕事', 'title' => '月次レポートをまとめる', 'status' => 'pending', 'due_date' => '2026-06-30', 'tags' => ['重要', '緊急']],
            ['email' => 'tanaka@example.com', 'category' => '学習', 'title' => 'テストの書き方を学ぶ', 'status' => 'in_progress', 'due_date' => null, 'tags' => ['重要', '今週中']],
            ['email' => 'tanaka@example.com', 'category' => 'プライベート', 'title' => '部屋を片付ける', 'status' => 'completed', 'due_date' => '2026-06-08', 'tags' => []],
        ];

        foreach ($tasks as $data) {
            $task = Task::firstOrCreate(
                [
                    'user_id' => $users[$data['email']]->id,
                    'title' => $data['title'],
                ],
                [
                    'category_id' => $categories[$data['category']]->id,
                    'status' => $data['status'],
                    'due_date' => $data['due_date'],
                    'description' => null,
                ],
            );

            $tagIds = collect($data['tags'])->map(fn ($name) => $tags[$name]->id)->toArray();
            $task->tags()->sync($tagIds);
        }
    }
}
