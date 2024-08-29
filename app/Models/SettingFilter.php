<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingFilter extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'data_cuoi_from',
        'data_cuoi_to',
        'reaction_chenh_from',
        'reaction_chenh_to',
        'delay',
        'status',
        'data_reaction_chenh_from',
        'data_reaction_chenh_to',
        'comment_chenh_from',
        'comment_chenh_to',
        'data_comment_chenh_from',
        'data_comment_chenh_to',
        'view_chenh_from',
        'view_chenh_to',
    ];
}
