<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\CategoryLesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('category')
                        ->latest()
                        ->paginate(10);
        
        return view('admin.lessons.index', compact('lessons'));
    }

    public function create()
    {
        $categories = CategoryLesson::all();
        return view('admin.lessons.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link_youtube' => 'required|url',
            'category_lesson_id' => 'required|exists:category_lessons,id'
        ], [
            'link_youtube.url' => __('messages.invalid_youtube_url'),
        ]);

        // Validate YouTube URL format
        $lesson = new Lesson($request->all());
        if (!$lesson->isValidYoutubeUrl()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['link_youtube' => __('messages.invalid_youtube_url_format')]);
        }

        Lesson::create($request->all());

        return redirect()->route('lessons.index')
            ->with('success', __('messages.lesson_created_successfully'));
    }

    public function show(Lesson $lesson)
    {
        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit(Lesson $lesson)
    {
        $categories = CategoryLesson::all();
        return view('admin.lessons.edit', compact('lesson', 'categories'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link_youtube' => 'required|url',
            'category_lesson_id' => 'required|exists:category_lessons,id'
        ], [
            'link_youtube.url' => __('messages.invalid_youtube_url'),
        ]);

        // Validate YouTube URL format
        $tempLesson = new Lesson($request->all());
        if (!$tempLesson->isValidYoutubeUrl()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['link_youtube' => __('messages.invalid_youtube_url_format')]);
        }

        $lesson->update($request->all());

        return redirect()->route('lessons.index')
            ->with('success', __('messages.lesson_updated_successfully'));
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('lessons.index')
            ->with('success', __('messages.lesson_deleted_successfully'));
    }

   
    public function searchByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $search = $request->search;

        $query = Lesson::with('category');

        if ($categoryId) {
            $query->where('category_lesson_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $lessons = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return view('lessons.partials.lessons-table', compact('lessons'))->render();
        }

        $categories = CategoryLesson::all();
        return view('admin.lessons.index', compact('lessons', 'categories'));
    }

    public function validateYoutubeUrl(Request $request)
    {
        $url = $request->url;
        $lesson = new Lesson(['link_youtube' => $url]);
        
        return response()->json([
            'valid' => $lesson->isValidYoutubeUrl(),
            'video_id' => $lesson->getYoutubeIdAttribute(),
            'thumbnail' => $lesson->getYoutubeThumbnailAttribute()
        ]);
    }


}