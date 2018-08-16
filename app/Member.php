<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table       = 'member';
    protected $guarded = ['id', 'delete_flg', 'create_date', 'update_date'];
    //const CREATED_AT = 'create_date';
    //const UPDATED_AT = 'update_date';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function fairs() {
        return $this->hasMany('App\Fair');
    }

    public function accounts() {
        return $this->hasMany('App\Account');
    }
}
