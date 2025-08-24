<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLesson extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
      public function parent()
    {
        return $this->belongsTo(CategoryLesson::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CategoryLesson::class, 'parent_id');
    }

     public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'category_lesson_id');
    }

    public function hasLessons()
    {
        return $this->lessons()->exists();
    }
}
