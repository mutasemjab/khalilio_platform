<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CategoryFile;
use Illuminate\Http\Request;

class CategoryFileController extends Controller
{
    public function index()
    {
        $categories = CategoryFile::with('parent', 'children')->paginate(10);
        return view('admin.category_files.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = CategoryFile::all();
        return view('admin.category_files.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:category_files,id'
        ]);

        CategoryFile::create($request->all());

        return redirect()->route('category_files.index')
            ->with('success', __('messages.category_created_successfully'));
    }

    public function show(CategoryFile $categoryFile)
    {
        $categoryFile->load('parent', 'children');
        return view('admin.category_files.show', compact('categoryFile'));
    }

    public function edit(CategoryFile $categoryFile)
    {
        $parentCategories = CategoryFile::where('id', '!=', $categoryFile->id)->get();
        return view('admin.category_files.edit', compact('categoryFile', 'parentCategories'));
    }

    public function update(Request $request, CategoryFile $categoryFile)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:category_files,id'
        ]);

        $categoryFile->update($request->all());

        return redirect()->route('category_files.index')
            ->with('success', __('messages.category_updated_successfully'));
    }

    public function destroy(CategoryFile $categoryFile)
    {
        $categoryFile->delete();

        return redirect()->route('category_files.index')
            ->with('success', __('messages.category_deleted_successfully'));
    }
}