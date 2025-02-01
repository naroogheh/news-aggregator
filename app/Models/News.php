<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    function category()
    {
        return $this->belongsTo(Category::class);
    }
    function source()
        {
        return $this->belongsTo(Source::class);
    }

    function newsAgency(){
        return $this->belongsTo(NewsAgency::class);
    }
    function author(){
        return $this->belongsTo(Author::class);
    }

}
