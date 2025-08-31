<?php

use App\Http\Controllers\Admin\CategoryExamController;
use App\Http\Controllers\Admin\CategoryFileController;
use App\Http\Controllers\Admin\CategoryLessonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;
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

define('PAGINATION_COUNT', 11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {



    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

        /*         start  update login admin                 */
        Route::get('/admin/edit/{id}', [LoginController::class, 'editlogin'])->name('admin.login.edit');
        Route::post('/admin/update/{id}', [LoginController::class, 'updatelogin'])->name('admin.login.update');
        /*         end  update login admin                */

        /// Role and permission
        Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController', ['as' => 'admin']);
        Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
        Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
        Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
        Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
        Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
        Route::post('admin/role/delete', 'App\Http\Controllers\Admin\RoleController@delete')->name('admin.role.delete');

        Route::get('/permissions/{guard_name}', function ($guard_name) {
            return response()->json(Permission::where('guard_name', $guard_name)->get());
        });


      
        Route::resource('users', UserController::class);
        Route::resource('category_exams', CategoryExamController::class);
        Route::resource('category_files', CategoryFileController::class);
        Route::resource('category_lessons', CategoryLessonController::class);
        Route::resource('pos', POSController::class);
        Route::resource('exams', ExamController::class);
        // Question Management Routes (Admin)
        Route::get('exams/{exam}/questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::get('exams/{exam}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('exams/{exam}/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::get('exams/{exam}/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
        Route::get('exams/{exam}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
        Route::put('exams/{exam}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('exams/{exam}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
        Route::post('exams/{exam}/questions/{question}/duplicate', [QuestionController::class, 'duplicate'])->name('questions.duplicate');
        Route::post('exams/{exam}/questions/reorder', [QuestionController::class, 'reorder'])->name('questions.reorder');

            // Admin Exam Management Routes
        Route::get('exams/{exam}/attempts', [ExamController::class, 'attempts'])->name('exams.attempts');
        Route::post('question-answers/{answer}/grade', [ExamController::class, 'gradeEssay'])->name('question-answer.grade');
        Route::get('exams/{exam}/attempts', [ExamController::class, 'attempts'])->name('exams.attempts');
        Route::get('exam-attempts/{attempt}/details', [ExamController::class, 'attemptDetails'])->name('exam-attempts.details');
        Route::delete('exam-attempts/{attempt}', [ExamController::class, 'deleteAttempt'])->name('exam-attempts.delete');
        Route::get('exams/{exam}/export-attempts', [ExamController::class, 'exportAttempts'])->name('exams.export-attempts');
        Route::get('exams/results', [ExamController::class, 'results'])->name('exams.results');

        // Essay Grading Routes
        Route::get('exam-attempts/{attempt}/grade-essays', [ExamController::class, 'gradeEssays'])->name('exam.grade-essays');
        Route::post('question-answers/{answer}/update-grade', [ExamController::class, 'updateEssayGrade'])->name('question-answer.update-grade');
            


        Route::resource('files', FileController::class);
        Route::post('files/search', [FileController::class, 'searchByCategory'])->name('files.search');

        Route::resource('lessons', LessonController::class);
        Route::post('lessons/search', [LessonController::class, 'searchByCategory'])->name('lessons.search');
        Route::post('lessons/validate-youtube', [LessonController::class, 'validateYoutubeUrl'])->name('lessons.validate-youtube');

});
});



Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
