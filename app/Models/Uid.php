<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uid extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uid',
        'phone',
    ];

    public function comment()
    {
        return $this->hasOne(Comment::class, 'uid', 'uid');
    }

    public function reaction()
    {
        return $this->hasOne(Reaction::class, 'uid', 'uid');
    }
}
