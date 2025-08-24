<?php

// app/Models/Exam.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'randomize_questions' => 'boolean',
        'show_results_immediately' => 'boolean',
        'is_active' => 'boolean',
        'instructions' => 'array',
        'total_grade' => 'decimal:2',
        'pass_grade' => 'decimal:2',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(CategoryExam::class, 'category_exam_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now);
    }

    // Helper methods
    public function isAvailable()
    {
        $now = now();
        return $this->is_active && 
               $this->start_time <= $now && 
               $this->end_time >= $now;
    }

    public function userAttempts($userId)
    {
        return $this->attempts()->where('user_id', $userId)->count();
    }

    public function canUserAttempt($userId)
    {
        return $this->isAvailable() && 
               $this->userAttempts($userId) < $this->max_attempts;
    }

    public function getUserBestScore($userId)
    {
        return $this->attempts()
                   ->where('user_id', $userId)
                   ->where('status', 'completed')
                   ->max('score');
    }

    public function getUserLastAttempt($userId)
    {
        return $this->attempts()
                   ->where('user_id', $userId)
                   ->latest()
                   ->first();
    }

    public function hasActiveAttempt($userId)
    {
        return $this->attempts()
                   ->where('user_id', $userId)
                   ->where('status', 'in_progress')
                   ->exists();
    }

    public function getQuestionsForUser($userId)
    {
        $questions = $this->questions;
        
        if ($this->randomize_questions) {
            return $questions->shuffle();
        }
        
        return $questions;
    }

    public function getDurationInSeconds()
    {
        return $this->duration_minutes * 60;
    }

    public function getStatusAttribute()
    {
        $now = now();
        
        if (!$this->is_active) {
            return 'inactive';
        }
        
        if ($now < $this->start_time) {
            return 'upcoming';
        }
        
        if ($now > $this->end_time) {
            return 'expired';
        }
        
        return 'available';
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return $hours . ' ساعة و ' . $minutes . ' دقيقة';
        }
        
        return $minutes . ' دقيقة';
    }
}