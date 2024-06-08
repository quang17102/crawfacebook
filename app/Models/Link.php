<?php

namespace App\Models;

use App\Constant\GlobalConstant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'time',
        'title',
        'content',
        'is_scan',
        'note',
        'link_or_post_id',
        'parent_link_or_post_id',
        'type',
        'end_cursor',
        'delay',
        'status',
        'image',
        'comment',
        'diff_comment',
        'data',
        'diff_data',
        'reaction',
        'diff_reaction',
        'user_id',
        'active',
        'is_on_at',
        'last_data_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(
            Comment::class,
            'link_or_post_id',
            'link_or_post_id'
        );
    }

    public function sameLinks()
    {
        return $this->hasMany(Link::class, 'link_or_post_id', 'link_or_post_id')
            ->orderBy('is_on_at');
    }

    public function userLinks()
    {
        return $this->hasMany(Link::class, 'parent_link_or_post_id', 'link_or_post_id')->orderBy('is_on_at');
    }

    public function userLinksWithTrashed()
    {
        return $this->hasMany(Link::class, 'parent_link_or_post_id', 'link_or_post_id')->withTrashed()->orderBy('is_on_at');
    }

    public function isOnUserLinks()
    {
        return $this->hasMany(Link::class, 'parent_link_or_post_id', 'link_or_post_id')
            ->where('is_scan', GlobalConstant::IS_ON)
            ->orderBy('is_on_at');
    }

    public function isFollowTypeUserLinks()
    {
        return $this->hasMany(Link::class, 'parent_link_or_post_id', 'link_or_post_id')
            ->where('type', GlobalConstant::TYPE_FOLLOW)
            ->orderBy('is_on_at');
    }

    public function reactionLinks()
    {
        return $this->hasMany(LinkReaction::class, 'link_id', 'id');
    }

    public function childLinks()
    {
        return $this->hasMany(Link::class, 'parent_link_or_post_id', 'link_or_post_id')->orderBy('id');
    }

    public function parentLink()
    {
        return $this->belongsTo(Link::class, 'parent_link_or_post_id', 'link_or_post_id');
    }
}
