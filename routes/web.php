<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosyatController;
use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    // Home route - shows registration if not logged in, dashboard if logged in
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Registration routes
    Route::match(['GET', 'POST'], '/register', [AuthController::class, 'register'])->name('register');
    Route::match(['GET', 'POST'], '/login', [AuthController::class, 'login'])->name('login');

    // Dashboard route - shows main categories after login
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Categories routes - shows subcategories for each main category type
    Route::get('/categories/{type}', [HomeController::class, 'showCategories'])->name('categories.show')
        ->where('type', 'exams|files|lessons');

    // Subcategories routes - shows children categories for a specific parent
    Route::get('/categories/{type}/{parentId}', [HomeController::class, 'showSubcategories'])->name('categories.subcategories')
        ->where(['type' => 'exams|files|lessons', 'parentId' => '[0-9]+']);

    // Direct access routes for final categories - ADD THESE MISSING ROUTES:
    Route::get('/files/{categoryId}', [HomeController::class, 'showCategoryFiles'])->name('category.files')
        ->where('categoryId', '[0-9]+');

    Route::get('/lessons/{categoryId}', [HomeController::class, 'showCategoryLessons'])->name('category.lessons')
        ->where('categoryId', '[0-9]+');

    Route::get('/exams/{categoryId}', [ExamController::class, 'showCategoryExams'])->name('category.exams')
        ->where('categoryId', '[0-9]+');

    // route for exams
    Route::get('/exam/{examId}', [ExamController::class, 'showExam'])->name('exam.show')
        ->where('examId', '[0-9]+');

    // Start a new exam attempt
    Route::post('/exam/{examId}/start', [ExamController::class, 'startExam'])->name('exam.start')
        ->where('examId', '[0-9]+');

    // Take exam - shows current question
    Route::get('/exam/{examId}/take/{attemptId}', [ExamController::class, 'takeExam'])->name('exam.take')
        ->where(['examId' => '[0-9]+', 'attemptId' => '[0-9]+']);

    // Save answer for current question (AJAX)
    Route::post('/exam/{examId}/take/{attemptId}/save-answer', [ExamController::class, 'saveAnswer'])->name('exam.save-answer')
        ->where(['examId' => '[0-9]+', 'attemptId' => '[0-9]+']);

    // Submit entire exam
    Route::post('/exam/{examId}/take/{attemptId}/submit', [ExamController::class, 'submitExam'])->name('exam.submit')
        ->where(['examId' => '[0-9]+', 'attemptId' => '[0-9]+']);

    // Show exam results
    Route::get('/exam/result/{attemptId}', [ExamController::class, 'showResult'])->name('exam.result')
        ->where('attemptId', '[0-9]+');

    // Get remaining time for active attempt (AJAX)
    Route::get('/exam/time-remaining/{attemptId}', [ExamController::class, 'getTimeRemaining'])->name('exam.time-remaining')
        ->where('attemptId', '[0-9]+');

    Route::get('/dosyat', [DosyatController::class, 'index'])->name('dosyat.index');
    Route::get('/dosyat/maktabat', [DosyatController::class, 'getMaktabat'])->name('dosyat.maktabat');
    Route::get('/dosyat/delivery', [DosyatController::class, 'getDelivery'])->name('dosyat.delivery');

    Route::get('/user/exam-history', [ExamController::class, 'userExamHistory'])->name('user.exam-history');
    
    // Logout route - clears session and redirects to home
    Route::get('/logout', function () {
        session()->flush();
        return redirect()->route('home');
    })->name('logout');
});
