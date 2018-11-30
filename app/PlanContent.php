<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanContent extends Model
{
    use SoftDeletes;

    protected $table  = 'plan_content';
    protected $guarded = ['plan_id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
