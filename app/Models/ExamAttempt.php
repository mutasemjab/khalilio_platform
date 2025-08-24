<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;
    protected $guarded = [];

     protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'answers' => 'array'
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionAnswers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    // Helper methods
    public function getRemainingTimeInSeconds()
    {
        if ($this->status !== 'in_progress') {
            return 0;
        }

        $examDurationSeconds = $this->exam->getDurationInSeconds();
        $elapsedSeconds = $this->started_at->diffInSeconds(now());
        
        return max(0, $examDurationSeconds - $elapsedSeconds);
    }

    public function isTimeUp()
    {
        return $this->getRemainingTimeInSeconds() <= 0;
    }

    public function calculateScore()
    {
        $totalScore = $this->questionAnswers()->sum('awarded_grade');
        $totalPossible = $this->exam->total_grade;
        
        $this->update([
            'score' => $totalScore,
            'percentage' => $totalPossible > 0 ? ($totalScore / $totalPossible) * 100 : 0
        ]);
        
        return $totalScore;
    }

    public function isPassed()
    {
        return $this->score >= $this->exam->pass_grade;
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'abandoned' => 'متروك',
            'time_up' => 'انتهى الوقت'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
}
