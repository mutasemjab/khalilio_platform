<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions()->orderBy('order')->paginate(10);
        return view('admin.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        $nextOrder = $exam->questions()->max('order') + 1;
        return view('admin.questions.create', compact('exam', 'nextOrder'));
    }

    public function store(Request $request, Exam $exam)
    {
        $validationRules = [
            'type' => 'required|in:multiple_choice,true_false,essay,fill_blank',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'grade' => 'required|numeric|min:0.1',
            'order' => 'required|integer|min:1',
        ];

        // Add type-specific validation
        if ($request->type === 'multiple_choice') {
            $validationRules['options'] = 'required|array|min:2';
            $validationRules['options.*'] = 'required|string';
            $validationRules['correct_answers'] = 'required|array|min:1';
            $validationRules['correct_answers.*'] = 'required|string';
        } elseif ($request->type === 'true_false') {
            $validationRules['correct_answers'] = 'required|in:true,false';
        }

        $request->validate($validationRules);

        $data = $request->except(['question_image']);

        // Handle image upload
        if ($request->hasFile('question_image')) {
            $imagePath = uploadImage('assets/admin/uploads', $request->question_image);
            $data['question_image'] = $imagePath;
        }

        // Prepare correct answers based on type
        if ($request->type === 'true_false') {
            $data['correct_answers'] = [$request->correct_answers];
        }

        $exam->questions()->create($data);

        // Update exam total grade
        $exam->update([
            'total_grade' => $exam->questions()->sum('grade')
        ]);

        return redirect()->route('questions.index', $exam)
            ->with('success', __('messages.question_created_successfully'));
    }

    public function show(Exam $exam, Question $question)
    {
        return view('admin.questions.show', compact('exam', 'question'));
    }

    public function edit(Exam $exam, Question $question)
    {
        return view('admin.questions.edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $validationRules = [
            'type' => 'required|in:multiple_choice,true_false,essay,fill_blank',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'grade' => 'required|numeric|min:0.1',
            'order' => 'required|integer|min:1',
        ];

        // Add type-specific validation
        if ($request->type === 'multiple_choice') {
            $validationRules['options'] = 'required|array|min:2';
            $validationRules['options.*'] = 'required|string';
            $validationRules['correct_answers'] = 'required|array|min:1';
            $validationRules['correct_answers.*'] = 'required|string';
        } elseif ($request->type === 'true_false') {
            $validationRules['correct_answers'] = 'required|in:true,false';
        }

        $request->validate($validationRules);

        $data = $request->except(['question_image']);

        // Handle image upload
        if ($request->hasFile('question_image')) {
            // Delete old image if exists
            if ($question->question_image) {
               $filePath = base_path('assets/admin/uploads/' . $question->question_image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $imagePath =  uploadImage('assets/admin/uploads', $request->question_image);
            $data['question_image'] = $imagePath;
        }

        // Prepare correct answers based on type
        if ($request->type === 'true_false') {
            $data['correct_answers'] = [$request->correct_answers];
        }

        $question->update($data);

        // Update exam total grade
        $exam->update([
            'total_grade' => $exam->questions()->sum('grade')
        ]);

        return redirect()->route('questions.index', $exam)
            ->with('success', __('messages.question_updated_successfully'));
    }

    public function destroy(Exam $exam, Question $question)
    {
        // Check if exam has attempts
        if ($exam->attempts()->count() > 0) {
            return redirect()->route('questions.index', $exam)
                ->with('error', __('messages.cannot_delete_question_exam_has_attempts'));
        }

        // Delete image if exists
        if ($question->question_image) {
           $filePath = base_path('assets/admin/uploads/' . $question->question_image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
        }

        $question->delete();

        // Update exam total grade
        $exam->update([
            'total_grade' => $exam->questions()->sum('grade')
        ]);

        return redirect()->route('questions.index', $exam)
            ->with('success', __('messages.question_deleted_successfully'));
    }

    public function reorder(Request $request, Exam $exam)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'required|integer|min:1'
        ]);

        foreach ($request->questions as $questionData) {
            Question::where('id', $questionData['id'])
                   ->where('exam_id', $exam->id)
                   ->update(['order' => $questionData['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function duplicate(Exam $exam, Question $question)
    {
        $newQuestion = $question->replicate();
        $newQuestion->order = $exam->questions()->max('order') + 1;
        $newQuestion->save();

        // Update exam total grade
        $exam->update([
            'total_grade' => $exam->questions()->sum('grade')
        ]);

        return redirect()->route('questions.index', $exam)
            ->with('success', __('messages.question_duplicated_successfully'));
    }
}