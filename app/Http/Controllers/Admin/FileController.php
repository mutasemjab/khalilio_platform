<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\CategoryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index()
    {
        $files = File::with('category')
                    ->latest()
                    ->paginate(10);
        
        return view('admin.files.index', compact('files'));
    }

    public function create()
    {
        $categories = CategoryFile::all();
        return view('admin.files.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'category_file_id' => 'required|exists:category_files,id'
        ]);

        $data = $request->except(['pdf']);

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $fileName = uploadImage('assets/admin/uploads', $request->pdf);
            $data['pdf'] = $fileName;
        }

        File::create($data);

        return redirect()->route('files.index')
            ->with('success', __('messages.file_created_successfully'));
    }

    public function show(File $file)
    {
        return view('admin.files.show', compact('file'));
    }

    public function edit(File $file)
    {
        $categories = CategoryFile::all();
        return view('admin.files.edit', compact('file', 'categories'));
    }

    public function update(Request $request, File $file)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'category_file_id' => 'required|exists:category_files,id'
        ]);

        $data = $request->except(['pdf']);

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
             if ($file->pdf) {
               $filePath = base_path('assets/admin/uploads/' . $file->pdf);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
             $fileName = uploadImage('assets/admin/uploads', $request->pdf);
            $data['pdf'] = $fileName;
        }

        $file->update($data);

        return redirect()->route('files.index')
            ->with('success', __('messages.file_updated_successfully'));
    }

    public function destroy(File $file)
    {
         if ($file->pdf) {
            $filePath = base_path('assets/admin/uploads/' . $file->pdf);
             if (file_exists($filePath)) {
                 unlink($filePath);
             }
         }
    
        // Delete the record
        $file->delete();

        return redirect()->route('files.index')
            ->with('success', __('messages.file_deleted_successfully'));
    }


    public function searchByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $search = $request->search;

        $query = File::with('category');

        if ($categoryId) {
            $query->where('category_file_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $files = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return view('files.partials.files-table', compact('files'))->render();
        }

        $categories = CategoryFile::all();
        return view('admin.files.index', compact('files', 'categories'));
    }
}