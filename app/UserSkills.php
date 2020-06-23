<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkills extends Model
{
    public function skill(){
        return $this->belongsTo(Skill::class);
    }
}
