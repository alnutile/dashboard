<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected static $unguarded = true;

    public function epic_sprint_record()
    {
        return $this->belongsTo(EpicSprintRecord::class);
    }
}
