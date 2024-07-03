<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'title',
        'uid',
        'phone',
        'content',
        'note',
        'name_facebook',
        'comment_id',
        'link_or_post_id',
        'created_at'
    ];

    public function getUid()
    {
        return $this->hasMany(Uid::class, 'uid', 'uid');
    }

    public function link()
    {
        return $this->hasOne(Link::class, 'link_or_post_id', 'link_or_post_id');
    }
}
