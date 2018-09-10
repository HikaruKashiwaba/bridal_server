<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $dates = ['delete_at'];
    protected $table = 'account';
    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
