<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'group';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
