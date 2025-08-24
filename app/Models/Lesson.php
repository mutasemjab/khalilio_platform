<?php

// app/Models/Lesson.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link_youtube',
        'category_lesson_id'
    ];

    // Relationship with CategoryLesson
    public function categoryLesson()
    {
        return $this->belongsTo(CategoryLesson::class, 'category_lesson_id');
    }

    // Alternative relationship name for consistency
    public function category()
    {
        return $this->belongsTo(CategoryLesson::class, 'category_lesson_id');
    }

    // Get YouTube video ID from URL
    public function getYoutubeIdAttribute()
    {
        $url = $this->link_youtube;
        
        // Extract YouTube video ID from various URL formats
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    // Get YouTube thumbnail URL
    public function getYoutubeThumbnailAttribute()
    {
        $videoId = $this->youtube_id;
        return $videoId ? "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg" : asset('assets_front/images/default-video.jpg');
    }

    // Get high quality thumbnail
    public function getYoutubeHdThumbnailAttribute()
    {
        $videoId = $this->youtube_id;
        return $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
    }

    // Get embed URL for iframe
    public function getEmbedUrlAttribute()
    {
        $videoId = $this->youtube_id;
        return $videoId ? "https://www.youtube.com/embed/{$videoId}?rel=0&showinfo=0&modestbranding=1" : null;
    }

    // Get watch URL
    public function getWatchUrlAttribute()
    {
        $videoId = $this->youtube_id;
        return $videoId ? "https://www.youtube.com/watch?v={$videoId}" : $this->link_youtube;
    }

    // Check if URL is valid YouTube URL
    public function isValidYoutubeUrl()
    {
        return !is_null($this->youtube_id);
    }

    // Get video duration (would need YouTube API for real duration)
    public function getDurationAttribute()
    {
        // Placeholder - would need YouTube Data API to get real duration
        return 'غير محدد';
    }

    // Get formatted creation date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }
}


