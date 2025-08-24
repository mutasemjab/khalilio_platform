<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'user_answer' => 'array',
        'awarded_grade' => 'decimal:2',
        'is_correct' => 'boolean',
        'answered_at' => 'datetime'
    ];

    // Relationships
    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Helper methods
    public function grade()
    {
        if ($this->is_correct !== null) {
            return $this->awarded_grade;
        }

        $isCorrect = $this->question->isCorrectAnswer($this->user_answer);
        
        if ($isCorrect !== null) {
            $grade = $isCorrect ? $this->question->grade : 0;
            
            $this->update([
                'is_correct' => $isCorrect,
                'awarded_grade' => $grade
            ]);
            
            return $grade;
        }
        
        return 0; // Essay questions need manual grading
    }
}