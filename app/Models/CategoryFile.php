<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFile extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
      public function parent()
    {
        return $this->belongsTo(CategoryFile::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CategoryFile::class, 'parent_id');
    }

     public function hasChildren()
    {
        return $this->children()->count() > 0;
    }
}
