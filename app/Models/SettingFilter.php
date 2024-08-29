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
        'reaction_from',
        'reaction_to',
        'delay',
        'status',
    ];
}
