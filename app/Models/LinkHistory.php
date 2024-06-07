<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkHistory extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'link_id',
        'comment',
        'diff_comment',
        'data',
        'diff_data',
        'reaction',
        'diff_reaction',
        'type',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
    }
}
