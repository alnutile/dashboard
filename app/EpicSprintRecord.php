<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpicSprintRecord extends Model
{
    protected static $unguarded = true;

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
