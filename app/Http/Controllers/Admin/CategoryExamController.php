<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryExam;
use Illuminate\Http\Request;

class CategoryExamController extends Controller
{
    public function index()
    {
        $categories = CategoryExam::with('parent', 'children')->paginate(10);
        return view('admin.category_exams.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = CategoryExam::all();
        return view('admin.category_exams.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:category_exams,id'
        ]);

        CategoryExam::create($request->all());

        return redirect()->route('category_exams.index')
            ->with('success', __('messages.category_created_successfully'));
    }

    public function show(CategoryExam $categoryExam)
    {
        $categoryExam->load('parent', 'children');
        return view('admin.category_exams.show', compact('categoryExam'));
    }

    public function edit(CategoryExam $categoryExam)
    {
        $parentCategories = CategoryExam::where('id', '!=', $categoryExam->id)->get();
        return view('admin.category_exams.edit', compact('categoryExam', 'parentCategories'));
    }

    public function update(Request $request, CategoryExam $categoryExam)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:category_exams,id'
        ]);

        $categoryExam->update($request->all());

        return redirect()->route('category_exams.index')
            ->with('success', __('messages.category_updated_successfully'));
    }

    public function destroy(CategoryExam $categoryExam)
    {
        $categoryExam->delete();

        return redirect()->route('category_exams.index')
            ->with('success', __('messages.category_deleted_successfully'));
    }
}
