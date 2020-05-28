<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title', 'description', 'time_min','time_max','characters_min','characters_max'
    ];

}
