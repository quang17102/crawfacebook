<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkReaction extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:H:i:s Y/m/d',
        'updated_at' => 'datetime:H:i:s Y/m/d',
    ];

    protected $fillable = [
        'reaction_id',
        'link_id',
    ];

    public function reaction()
    {
        return $this->belongsTo(Reaction::class, 'reaction_id', 'id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id', 'id');
    }
}
