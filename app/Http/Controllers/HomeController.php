<?php

namespace App\Http\Controllers;

use App\Models\CategoryExam;
use App\Models\CategoryFile;
use App\Models\CategoryLesson;
use App\Models\File;
use App\Models\Lesson;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            $fields = Field::all();
            return view('sections.registration', compact('fields'));
        }

        return $this->dashboard();
    }

    public function dashboard()
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $mainCategories = $this->getMainCategories();
        return view('sections.dashboard', compact('userName', 'mainCategories'));
    }

    private function getMainCategories()
    {
        $categories = [];

        $examRoot = CategoryExam::whereNull('parent_id')->first();
        if ($examRoot) {
            $categories[] = [
                'id' => $examRoot->id,
                'name' => $examRoot->name,
                'type' => 'exams',
                'icon' => 'fas fa-file-alt',
                'description' => 'اختبر معلوماتك وقس مستواك الأكاديمي',
                'color' => '#000000ff'
            ];
        }

        $fileRoot = CategoryFile::whereNull('parent_id')->first();
        if ($fileRoot) {
            $categories[] = [
                'id' => $fileRoot->id,
                'name' => $fileRoot->name,
                'type' => 'files',
                'icon' => 'fas fa-file-pdf',
                'description' => 'تصفح وحمل الملفات والمراجع الدراسية',
                'color' => '#000000ff'
            ];
        }

        $lessonRoot = CategoryLesson::whereNull('parent_id')->first();
        if ($lessonRoot) {
            $categories[] = [
                'id' => $lessonRoot->id,
                'name' => $lessonRoot->name,
                'type' => 'lessons',
                'icon' => 'fas fa-play-circle',
                'description' => 'شاهد الدروس والشروحات التفاعلية',
                'color' => '#000000ff'
            ];
        }

        return $categories;
    }

    public function showCategories($type)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $categories = collect();
        $categoryTitle = '';

        switch ($type) {
            case 'exams':
                $rootCategory = CategoryExam::whereNull('parent_id')->first();
                if ($rootCategory) {
                    $categories = CategoryExam::where('parent_id', $rootCategory->id)->get();
                    $categoryTitle = $rootCategory->name;
                }
                break;
                
            case 'files':
                $rootCategory = CategoryFile::whereNull('parent_id')->first();
                if ($rootCategory) {
                    $categories = CategoryFile::where('parent_id', $rootCategory->id)->get();
                    $categoryTitle = $rootCategory->name;
                }
                break;
                
            case 'lessons':
                $rootCategory = CategoryLesson::whereNull('parent_id')->first();
                if ($rootCategory) {
                    $categories = CategoryLesson::where('parent_id', $rootCategory->id)->get();
                    $categoryTitle = $rootCategory->name;
                }
                break;
                
            default:
                return redirect()->route('dashboard');
        }

        return view('sections.categories', compact('userName', 'categories', 'categoryTitle', 'type'));
    }

    public function showSubcategories($type, $parentId)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $categories = collect();
        $categoryTitle = '';
        $parentCategory = null;

        switch ($type) {
            case 'exams':
                $parentCategory = CategoryExam::find($parentId);
                if ($parentCategory) {
                    $categories = CategoryExam::where('parent_id', $parentCategory->id)->get();
                    $categoryTitle = $parentCategory->name;
                }
                break;
                
            case 'files':
                $parentCategory = CategoryFile::find($parentId);
                if ($parentCategory) {
                    $categories = CategoryFile::where('parent_id', $parentCategory->id)->get();
                    $categoryTitle = $parentCategory->name;
                    
                    // Check if this category has no children but has files
                    if ($categories->isEmpty()) {
                        $files = File::where('category_file_id', $parentCategory->id)->get();
                        if ($files->isNotEmpty()) {
                            return $this->showFiles($type, $parentId, $parentCategory, $files, $userName);
                        }
                    }
                }
                break;
                
            case 'lessons':
                $parentCategory = CategoryLesson::find($parentId);
                if ($parentCategory) {
                    $categories = CategoryLesson::where('parent_id', $parentCategory->id)->get();
                    $categoryTitle = $parentCategory->name;
                    
                    // Check if this category has no children but has lessons
                    if ($categories->isEmpty()) {
                        $lessons = Lesson::where('category_lesson_id', $parentCategory->id)->get();
                        if ($lessons->isNotEmpty()) {
                            return $this->showLessons($type, $parentId, $parentCategory, $lessons, $userName);
                        }
                    }
                }
                break;
                
            default:
                return redirect()->route('dashboard');
        }

        if (!$parentCategory) {
            return redirect()->route('categories.show', $type);
        }

        $isRoot = ($parentCategory->parent_id === null);
        $backRoute = $isRoot ? 'dashboard' : 'categories.show';
        $backParams = $isRoot ? [] : [$type];

        return view('sections.subcategories', compact('userName', 'categories', 'categoryTitle', 'type', 'parentId', 'parentCategory', 'backRoute', 'backParams'));
    }

    private function showFiles($type, $parentId, $parentCategory, $files, $userName)
    {
        $categoryTitle = $parentCategory->name;
        
        $isRoot = ($parentCategory->parent_id === null);
        $backRoute = $isRoot ? 'dashboard' : 'categories.subcategories';
        $backParams = $isRoot ? [] : [$type, $parentCategory->parent_id];

        return view('sections.files', compact('userName', 'files', 'categoryTitle', 'type', 'parentId', 'parentCategory', 'backRoute', 'backParams'));
    }

    private function showLessons($type, $parentId, $parentCategory, $lessons, $userName)
    {
        $categoryTitle = $parentCategory->name;
        
        $isRoot = ($parentCategory->parent_id === null);
        $backRoute = $isRoot ? 'dashboard' : 'categories.subcategories';
        $backParams = $isRoot ? [] : [$type, $parentCategory->parent_id];

        return view('sections.lessons', compact('userName', 'lessons', 'categoryTitle', 'type', 'parentId', 'parentCategory', 'backRoute', 'backParams'));
    }

    // Method to handle direct file viewing for specific category
    public function showCategoryFiles($categoryId)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $parentCategory = CategoryFile::find($categoryId);
        if (!$parentCategory) {
            return redirect()->route('categories.show', 'files');
        }

        $files = File::where('category_file_id', $categoryId)->get();
        
        return $this->showFiles('files', $categoryId, $parentCategory, $files, $userName);
    }

    // Method to handle direct lesson viewing for specific category
    public function showCategoryLessons($categoryId)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $parentCategory = CategoryLesson::find($categoryId);
        if (!$parentCategory) {
            return redirect()->route('categories.show', 'lessons');
        }

        $lessons = Lesson::where('category_lesson_id', $categoryId)->get();
        
        return $this->showLessons('lessons', $categoryId, $parentCategory, $lessons, $userName);
    }
}