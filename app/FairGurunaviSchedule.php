<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FairGurunaviSchedule extends Model
{
    //
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'fair_gurunavi_schedule';
    protected $guarded = ['id', 'delete_flg', 'created_at', 'updated_at'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
