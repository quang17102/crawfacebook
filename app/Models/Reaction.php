<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'title',
        'uid',
        'phone',
        'reaction',
        'content',
        'name_facebook',
        'note',
        'link_or_post_id',
    ];

    public function getUid()
    {
        return $this->hasMany(Uid::class, 'uid', 'uid');
    }

    public function reactionLinks()
    {
        return $this->hasMany(LinkReaction::class, 'reaction_id', 'id');
    }

    public function link()
    {
        return $this->hasOne(Link::class, 'link_or_post_id', 'link_or_post_id');
    }
}
