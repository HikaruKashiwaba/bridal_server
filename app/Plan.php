<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    protected $table  = 'plan';
    protected $guarded = ['plan_id'];
    protected $primaryKey = 'plan_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}