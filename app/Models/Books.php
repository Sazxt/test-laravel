<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    //
    use HasFactory;
    protected $fillable = ["title", "author_id", "category_id", "publication_date", "file_path"];

    public function author()
    {
        return $this->belongsTo(Authors::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
