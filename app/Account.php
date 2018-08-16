<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table       = 'account';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
