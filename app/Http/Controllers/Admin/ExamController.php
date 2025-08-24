<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\CategoryExam;
use App\Models\ExamAttempt;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('category')
                    ->latest()
                    ->paginate(10);
        
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $categories = CategoryExam::all();
        return view('admin.exams.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_exam_id' => 'required|exists:category_exams,id',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'total_grade' => 'required|numeric|min:0',
            'pass_grade' => 'required|numeric|min:0|lte:total_grade',
            'max_attempts' => 'required|integer|min:1',
        ]);

        Exam::create($request->all());

        return redirect()->route('exams.index')
            ->with('success', __('messages.exam_created_successfully'));
    }

    public function show(Exam $exam)
    {
        $exam->load(['questions', 'category', 'attempts.user']);
        
        // Get user's attempts if logged in
        $userAttempts = null;
        if (Auth::check()) {
            $userAttempts = $exam->attempts()
                               ->where('user_id', Auth::id())
                               ->latest()
                               ->get();
        }

        return view('admin.exams.show', compact('exam', 'userAttempts'));
    }

    public function edit(Exam $exam)
    {
        $categories = CategoryExam::all();
        return view('admin.exams.edit', compact('exam', 'categories'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_exam_id' => 'required|exists:category_exams,id',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'total_grade' => 'required|numeric|min:0',
            'pass_grade' => 'required|numeric|min:0|lte:total_grade',
            'max_attempts' => 'required|integer|min:1',
        ]);

        $exam->update($request->all());

        return redirect()->route('exams.index')
            ->with('success', __('messages.exam_updated_successfully'));
    }

    public function destroy(Exam $exam)
    {
        // Check if exam has attempts
        if ($exam->attempts()->count() > 0) {
            return redirect()->route('exams.index')
                ->with('error', __('messages.exam_has_attempts_cannot_delete'));
        }

        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', __('messages.exam_deleted_successfully'));
    }


    public function attempts(Exam $exam)
    {
        $attempts = $exam->attempts()
                        ->with(['user.field'])
                        ->latest()
                        ->paginate(20);

        return view('admin.exams.attempts', compact('exam', 'attempts'));
    }

    public function attemptDetails($attemptId)
    {
        $attempt = ExamAttempt::with(['user.field', 'exam', 'questionAnswers.question'])
                            ->findOrFail($attemptId);
        
        $html = view('admin.exams.attempt-details-modal', compact('attempt'))->render();
        
        return response()->json(['html' => $html]);
    }

    public function deleteAttempt($attemptId)
    {
        try {
            $attempt = ExamAttempt::findOrFail($attemptId);
            
            // Check if user has permission to delete this attempt
            // Add your authorization logic here
            
            $attempt->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('messages.attempt_deleted_successfully')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error_deleting_attempt')
            ], 500);
        }
    }

    public function exportAttempts(Exam $exam)
    {
        $attempts = $exam->attempts()
                        ->with(['user.field'])
                        ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="exam_attempts_' . $exam->id . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($attempts, $exam) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                __('messages.student_name'),
                __('messages.phone'),
                __('messages.school_name'),
                __('messages.field'),
                __('messages.status'),
                __('messages.started_at'),
                __('messages.submitted_at'),
                __('messages.duration_minutes'),
                __('messages.score'),
                __('messages.percentage'),
                __('messages.result'),
                __('messages.notes')
            ]);

            foreach ($attempts as $attempt) {
                $duration = null;
                if ($attempt->submitted_at) {
                    $duration = $attempt->started_at->diffInMinutes($attempt->submitted_at);
                }

                $result = '';
                if ($attempt->status === 'completed' && $attempt->score !== null) {
                    $result = $attempt->score >= $exam->pass_grade ? __('messages.passed') : __('messages.failed');
                }

                fputcsv($file, [
                    $attempt->user->name,
                    $attempt->user->phone,
                    $attempt->user->school_name,
                    $attempt->user->field->name ?? '',
                    $attempt->status,
                    $attempt->started_at->format('Y-m-d H:i:s'),
                    $attempt->submitted_at ? $attempt->submitted_at->format('Y-m-d H:i:s') : '',
                    $duration,
                    $attempt->score ?? '',
                    $attempt->percentage ? number_format($attempt->percentage, 2) . '%' : '',
                    $result,
                    $attempt->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function gradeEssays(ExamAttempt $attempt)
    {
        // Load attempt with essay questions and answers
        $attempt->load([
            'exam.questions' => function($query) {
                $query->where('type', 'essay');
            },
            'questionAnswers' => function($query) {
                $query->whereHas('question', function($q) {
                    $q->where('type', 'essay');
                });
            },
            'user.field'
        ]);

        return view('exams.grade-essays', compact('attempt'));
    }

    public function updateEssayGrade(Request $request, QuestionAnswer $answer)
    {
        $request->validate([
            'awarded_grade' => 'required|numeric|min:0|max:' . $answer->question->grade,
            'feedback' => 'nullable|string|max:1000'
        ]);

        $answer->update([
            'awarded_grade' => $request->awarded_grade,
            'feedback' => $request->feedback,
            'is_correct' => $request->awarded_grade > 0
        ]);

        // Recalculate attempt score
        $answer->examAttempt->calculateScore();

        return redirect()->back()
            ->with('success', __('messages.essay_graded_successfully'));
    }


}