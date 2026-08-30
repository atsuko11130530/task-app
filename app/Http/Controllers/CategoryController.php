<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('tasks')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'カテゴリを作成しました。');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'カテゴリを更新しました。');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->tasks()->exists()) {
            return redirect()->route('categories.index')->with('error', 'このカテゴリにはタスクが紐づいているため削除できません。');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'カテゴリを削除しました。');
    }
}
