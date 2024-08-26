<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'board',
        'score_x',
        'score_o',
        'current_turn',
        'victory'
    ];

    protected $casts = [
        'board' => 'array'
    ];

}
