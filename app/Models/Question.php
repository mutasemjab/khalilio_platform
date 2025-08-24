<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'grade' => 'decimal:2',
        'is_required' => 'boolean',
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    // Helper methods
    public function isCorrectAnswer($userAnswer)
    {
        if ($this->type === 'multiple_choice') {
            return in_array($userAnswer, $this->correct_answers ?? []);
        }
        
        if ($this->type === 'true_false') {
            return $userAnswer === ($this->correct_answers[0] ?? null);
        }
        
        if ($this->type === 'fill_blank') {
            $correctAnswers = array_map('trim', array_map('strtolower', $this->correct_answers ?? []));
            $userAnswerTrimmed = trim(strtolower($userAnswer));
            return in_array($userAnswerTrimmed, $correctAnswers);
        }
        
        // For essay questions, manual grading is required
        return null;
    }

    public function calculateGrade($userAnswer)
    {
        $isCorrect = $this->isCorrectAnswer($userAnswer);
        
        if ($isCorrect === null) {
            // Essay question - requires manual grading
            return 0;
        }
        
        return $isCorrect ? $this->grade : 0;
    }

    public function getQuestionImageUrlAttribute()
    {
        return $this->question_image ? asset('assets/admin/uploads/' . $this->question_image) : null;
    }

    public function getTypeNameAttribute()
    {
        $types = [
            'multiple_choice' => 'اختيار متعدد',
            'true_false' => 'صح أم خطأ',
            'essay' => 'مقالي',
            'fill_blank' => 'املأ الفراغ'
        ];
        
        return $types[$this->type] ?? $this->type;
    }
}

