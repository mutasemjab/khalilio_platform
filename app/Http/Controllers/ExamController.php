<?php

// app/Http/Controllers/ExamController.php
namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\QuestionAnswer;
use App\Models\CategoryExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function showCategoryExams($categoryId)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $parentCategory = CategoryExam::find($categoryId);
        if (!$parentCategory) {
            return redirect()->route('categories.show', 'exams');
        }

        $exams = Exam::where('category_exam_id', $categoryId)
                    ->active()
                    ->with('questions')
                    ->get();
        
        // Add user attempt information
        foreach ($exams as $exam) {
            $exam->user_attempts_count = $exam->userAttempts($userId);
            $exam->can_attempt = $exam->canUserAttempt($userId);
            $exam->best_score = $exam->getUserBestScore($userId);
            $exam->has_active_attempt = $exam->hasActiveAttempt($userId);
            
            // DEBUG INFO - REMOVE AFTER FIXING
            \Log::info('Exam Debug', [
                'exam_id' => $exam->id,
                'exam_name' => $exam->name,
                'max_attempts' => $exam->max_attempts,
                'user_attempts_count' => $exam->user_attempts_count,
                'is_available' => $exam->isAvailable(),
                'can_attempt' => $exam->can_attempt,
                'status' => $exam->status,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'is_active' => $exam->is_active
            ]);
        }

        $categoryTitle = $parentCategory->name;
        $isRoot = ($parentCategory->parent_id === null);
        $backRoute = $isRoot ? 'dashboard' : 'categories.subcategories';
        $backParams = $isRoot ? [] : ['exams', $parentCategory->parent_id];

        return view('sections.exams', compact(
            'userName', 'exams', 'categoryTitle', 'parentCategory', 
            'backRoute', 'backParams', 'categoryId'
        ));
    }

   public function showExam($examId)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $exam = Exam::with(['questions', 'category'])->findOrFail($examId);
        
        if (!$exam->isAvailable()) {
            return redirect()->back()->with('error', 'هذا الامتحان غير متاح حالياً');
        }

        // Check if user has active attempt
        $activeAttempt = $exam->attempts()
                            ->where('user_id', $userId)
                            ->where('status', 'in_progress')
                            ->first();

        if ($activeAttempt) {
            return redirect()->route('exam.take', ['examId' => $examId, 'attemptId' => $activeAttempt->id]);
        }

        // ADD THESE LINES - Set all the properties that the template expects
        $exam->user_attempts_count = $exam->userAttempts($userId);
        $exam->can_attempt = $exam->canUserAttempt($userId);
        $exam->best_score = $exam->getUserBestScore($userId);
        $exam->has_active_attempt = $exam->hasActiveAttempt($userId);

        // These are still needed for the template
        $userAttempts = $exam->userAttempts($userId);
        $bestScore = $exam->getUserBestScore($userId);

        return view('sections.exam-details', compact(
            'userName', 'exam', 'userAttempts', 'bestScore'
        ));
    }

  public function startExam($examId)
    {
        $userId = Session::get('user_id');

        // DEBUG LOG
        \Log::info('startExam called', [
            'examId' => $examId,
            'userId' => $userId,
            'session_data' => session()->all()
        ]);

        if (!$userId) {
            \Log::error('startExam: No user ID in session');
            return redirect()->route('home');
        }

        $exam = Exam::findOrFail($examId);
        
        // DEBUG LOG
        \Log::info('startExam: Exam found', [
            'exam_id' => $exam->id,
            'exam_name' => $exam->name,
            'can_attempt' => $exam->canUserAttempt($userId)
        ]);

        if (!$exam->canUserAttempt($userId)) {
            \Log::error('startExam: User cannot attempt exam', [
                'userId' => $userId,
                'examId' => $examId,
                'max_attempts' => $exam->max_attempts,
                'user_attempts' => $exam->userAttempts($userId)
            ]);
            return redirect()->back()->with('error', 'لا يمكنك بدء هذا الامتحان');
        }

        // Check for existing active attempt
        $existingAttempt = $exam->attempts()
                            ->where('user_id', $userId)
                            ->where('status', 'in_progress')
                            ->first();

        if ($existingAttempt) {
            \Log::info('startExam: Found existing attempt', ['attempt_id' => $existingAttempt->id]);
            return redirect()->route('exam.take', ['examId' => $examId, 'attemptId' => $existingAttempt->id]);
        }

        // Add missing import for DB
        DB::beginTransaction();
        try {
            // Create new attempt
            $attempt = ExamAttempt::create([
                'exam_id' => $examId,
                'user_id' => $userId,
                'started_at' => now(),
                'status' => 'in_progress'
            ]);

            \Log::info('startExam: Created new attempt', ['attempt_id' => $attempt->id]);

            // Create question answers for tracking
            $questions = $exam->getQuestionsForUser($userId);
            \Log::info('startExam: Questions count', ['count' => $questions->count()]);

            foreach ($questions as $question) {
                QuestionAnswer::create([
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $question->id
                ]);
            }

            DB::commit();
            
            \Log::info('startExam: Success, redirecting to exam.take', [
                'examId' => $examId,
                'attemptId' => $attempt->id
            ]);

            return redirect()->route('exam.take', ['examId' => $examId, 'attemptId' => $attempt->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('startExam: Database error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء بدء الامتحان: ' . $e->getMessage());
        }
    }


    public function takeExam($examId, $attemptId, Request $request)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('home');
        }

        $attempt = ExamAttempt::with(['exam.questions', 'questionAnswers'])
                            ->where('id', $attemptId)
                            ->where('user_id', $userId)
                            ->where('status', 'in_progress')
                            ->firstOrFail();

        // Check if time is up
        if ($attempt->isTimeUp()) {
            $this->autoSubmitExam($attempt);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'timeUp' => true, 
                    'redirect' => route('exam.result', $attempt->id)
                ]);
            }
            return redirect()->route('exam.result', $attempt->id);
        }

        $questions = $attempt->exam->getQuestionsForUser($userId);
        $currentQuestionIndex = $request->get('q', 0);
        $currentQuestionIndex = max(0, min($currentQuestionIndex, $questions->count() - 1));
        $currentQuestion = $questions->get($currentQuestionIndex);

        if (!$currentQuestion) {
            $currentQuestionIndex = 0;
            $currentQuestion = $questions->first();
        }

        // Get user's previous answer for this question
        $previousAnswer = $attempt->questionAnswers()
                                ->where('question_id', $currentQuestion->id)
                                ->first();

        $progress = [
            'current' => $currentQuestionIndex + 1,
            'total' => $questions->count(),
            'percentage' => round((($currentQuestionIndex + 1) / $questions->count()) * 100, 1)
        ];

        // If this is an AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            // Generate answer HTML based on question type
            $answerHtml = $this->generateAnswerHtml($currentQuestion, $previousAnswer);
            
            return response()->json([
                'success' => true,
                'question' => [
                    'id' => $currentQuestion->id,
                    'type' => $currentQuestion->type,
                    'type_name' => $this->getQuestionTypeName($currentQuestion->type),
                    'question_text' => $currentQuestion->question_text,
                    'grade' => $currentQuestion->grade,
                    'question_image' => $currentQuestion->question_image,
                    'question_image_url' => $currentQuestion->question_image ? asset('storage/' . $currentQuestion->question_image) : null
                ],
                'answerHtml' => $answerHtml,
                'previousAnswer' => $previousAnswer,
                'progress' => $progress,
                'currentQuestionIndex' => $currentQuestionIndex
            ]);
        }

        // Regular view response for non-AJAX requests
        return view('sections.exam-taking', compact(
            'userName', 'attempt', 'questions', 'currentQuestion', 
            'currentQuestionIndex', 'previousAnswer', 'progress'
        ));
    }

    private function generateAnswerHtml($question, $previousAnswer = null)
    {
        $html = '';
        
        try {
            switch ($question->type) {
                case 'multiple_choice':
                    $html = view('includes.question-types.multiple-choice', compact('question', 'previousAnswer'))->render();
                    break;
                    
                case 'true_false':
                    $html = view('includes.question-types.true-false', compact('question', 'previousAnswer'))->render();
                    break;
                    
                case 'essay':
                    $html = view('includes.question-types.essay', compact('question', 'previousAnswer'))->render();
                    break;
                    
                case 'fill_blank':
                    $html = view('includes.question-types.fill-blank', compact('question', 'previousAnswer'))->render();
                    break;
                    
                default:
                    $html = '<p>نوع السؤال غير مدعوم</p>';
            }
        } catch (\Exception $e) {
            \Log::error('Error generating answer HTML', [
                'question_id' => $question->id,
                'question_type' => $question->type,
                'error' => $e->getMessage()
            ]);
            
            // Fallback HTML
            $html = $this->getFallbackAnswerHtml($question, $previousAnswer);
        }
        
        return $html;
    }

    private function getFallbackAnswerHtml($question, $previousAnswer = null)
    {
        $userAnswer = $previousAnswer ? $previousAnswer->user_answer : '';
        
        switch ($question->type) {
            case 'multiple_choice':
                $options = json_decode($question->options, true) ?: [];
                $html = '<div class="question-options">';
                foreach ($options as $index => $option) {
                    $letter = chr(65 + $index); // A, B, C, D
                    $checked = ($userAnswer == $option) ? 'checked' : '';
                    $html .= "
                        <label class='option-label'>
                            <input type='radio' name='answer' value='{$option}' class='option-input' {$checked}>
                            <div class='option-content'>
                                <span class='option-letter'>{$letter}</span>
                                <span class='option-text'>{$option}</span>
                            </div>
                        </label>
                    ";
                }
                $html .= '</div>';
                return $html;
                
            case 'true_false':
                $trueChecked = ($userAnswer == 'true') ? 'checked' : '';
                $falseChecked = ($userAnswer == 'false') ? 'checked' : '';
                return "
                    <div class='question-options'>
                        <label class='option-label'>
                            <input type='radio' name='answer' value='true' class='option-input' {$trueChecked}>
                            <div class='option-content'>
                                <span class='option-letter'>صح</span>
                                <span class='option-text'>صحيح</span>
                            </div>
                        </label>
                        <label class='option-label'>
                            <input type='radio' name='answer' value='false' class='option-input' {$falseChecked}>
                            <div class='option-content'>
                                <span class='option-letter'>خطأ</span>
                                <span class='option-text'>خطأ</span>
                            </div>
                        </label>
                    </div>
                ";
                
            case 'essay':
                return "<textarea id='essayAnswer' class='essay-textarea' placeholder='اكتب إجابتك هنا...'>{$userAnswer}</textarea>";
                
            case 'fill_blank':
                return "<input type='text' id='fillBlankAnswer' class='fill-blank-input' value='{$userAnswer}' placeholder='أدخل الإجابة'>";
                
            default:
                return '<p>نوع السؤال غير مدعوم</p>';
        }
    }

    private function getQuestionTypeName($type)
    {
        $typeNames = [
            'multiple_choice' => 'اختيار متعدد',
            'true_false' => 'صواب وخطأ',
            'essay' => 'سؤال مقالي',
            'fill_blank' => 'ملء الفراغات'
        ];
        
        return $typeNames[$type] ?? 'غير محدد';
    }

    public function saveAnswer(Request $request, $examId, $attemptId)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $attempt = ExamAttempt::where('id', $attemptId)
                             ->where('user_id', $userId)
                             ->where('status', 'in_progress')
                             ->firstOrFail();

        // Check if time is up
        if ($attempt->isTimeUp()) {
            return response()->json(['timeUp' => true, 'redirect' => route('exam.result', $attemptId)]);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'nullable'
        ]);

        $questionAnswer = QuestionAnswer::where('exam_attempt_id', $attemptId)
                                       ->where('question_id', $request->question_id)
                                       ->firstOrFail();

        $questionAnswer->update([
            'user_answer' => $request->answer,
            'answered_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function submitExam($examId, $attemptId)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('home');
        }

        $attempt = ExamAttempt::where('id', $attemptId)
                             ->where('user_id', $userId)
                             ->where('status', 'in_progress')
                             ->firstOrFail();

        $this->processSubmission($attempt);

        return redirect()->route('exam.result', $attemptId);
    }

    private function autoSubmitExam($attempt)
    {
        $attempt->update(['status' => 'time_up']);
        $this->processSubmission($attempt, 'time_up');
    }

    private function processSubmission($attempt, $status = 'completed')
    {
        DB::beginTransaction();
        try {
            // Grade all questions
            $totalScore = 0;
            foreach ($attempt->questionAnswers as $questionAnswer) {
                $grade = $questionAnswer->grade();
                $totalScore += $grade;
            }

            $exam = $attempt->exam;
            $percentage = $exam->total_grade > 0 ? ($totalScore / $exam->total_grade) * 100 : 0;

            $attempt->update([
                'status' => $status,
                'submitted_at' => now(),
                'score' => $totalScore,
                'percentage' => $percentage
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function showResult($attemptId)
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        $attempt = ExamAttempt::with(['exam', 'questionAnswers.question'])
                             ->where('id', $attemptId)
                             ->where('user_id', $userId)
                             ->firstOrFail();

        if ($attempt->status === 'in_progress') {
            return redirect()->route('exam.take', ['examId' => $attempt->exam_id, 'attemptId' => $attemptId]);
        }

        return view('sections.exam-result', compact('userName', 'attempt'));
    }

    public function getTimeRemaining($attemptId)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $attempt = ExamAttempt::where('id', $attemptId)
                             ->where('user_id', $userId)
                             ->where('status', 'in_progress')
                             ->first();

        if (!$attempt) {
            return response()->json(['error' => 'Attempt not found'], 404);
        }

        $remainingSeconds = $attempt->getRemainingTimeInSeconds();

        if ($remainingSeconds <= 0) {
            $this->autoSubmitExam($attempt);
            return response()->json([
                'timeUp' => true, 
                'redirect' => route('exam.result', $attemptId)
            ]);
        }

        return response()->json(['remainingSeconds' => $remainingSeconds]);
    }
}