<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $guarded = [];

    public function users()
    {
    	return $this->belongsToMany(User::class);
    }
}
