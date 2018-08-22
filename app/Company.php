<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table       = 'company';
    protected $guarded = ['id', 'delete_flg', 'create_date', 'update_date'];
    // const CREATED_AT = 'create_date';
    // const UPDATED_AT = 'update_date';

    public function members() {
	return $this->hasMany('App\Member');
    }
}
