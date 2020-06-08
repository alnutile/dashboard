<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected static $unguarded = true;

    // @codingStandardsIgnoreStart
    public function epic_sprint_record()
    {
        // @codingStandardsIgnoreEnd
        return $this->belongsTo(EpicSprintRecord::class);
    }
}
