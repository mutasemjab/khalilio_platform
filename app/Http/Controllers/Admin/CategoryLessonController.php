<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CategoryLesson;
use Illuminate\Http\Request;

class CategoryLessonController extends Controller
{
    public function index()
    {
        $categories = CategoryLesson::with('parent', 'children')->paginate(10);
        return view('admin.category_lessons.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = CategoryLesson::all();
        return view('admin.category_lessons.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:category_lessons,id'
        ]);

        CategoryLesson::create($request->all());

        return redirect()->route('category_lessons.index')
            ->with('success', __('messages.category_created_successfully'));
    }

    public function show(CategoryLesson $categoryLesson)
    {
        $categoryLesson->load('parent', 'children');
        return view('admin.category_lessons.show', compact('categoryLesson'));
    }

    public function edit(CategoryLesson $categoryLesson)
    {
        $parentCategories = CategoryLesson::where('id', '!=', $categoryLesson->id)->get();
        return view('admin.category_lessons.edit', compact('categoryLesson', 'parentCategories'));
    }

    public function update(Request $request, CategoryLesson $categoryLesson)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:category_lessons,id'
        ]);

        $categoryLesson->update($request->all());

        return redirect()->route('category_lessons.index')
            ->with('success', __('messages.category_updated_successfully'));
    }

    public function destroy(CategoryLesson $categoryLesson)
    {
        $categoryLesson->delete();

        return redirect()->route('category_lessons.index')
            ->with('success', __('messages.category_deleted_successfully'));
    }
}