<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;

class Photos extends Model
{
    protected $fillable = [
        "user_id",
        "title",
        "image_path"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'photo_id');
    }
}