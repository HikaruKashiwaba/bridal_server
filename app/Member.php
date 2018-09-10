<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table       = 'member';
    protected $guarded = ['id', 'company_id', 'create_at', 'update_at'];

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
