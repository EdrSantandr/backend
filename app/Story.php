<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'title', 'content', 'votes','turns','date_ini','date_end','state','last_user_id'
    ];

}
