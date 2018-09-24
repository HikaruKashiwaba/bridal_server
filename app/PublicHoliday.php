<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    //
    protected $table       = 'public_holiday';
    protected $guarded = ['id', 'holiday'];
}
