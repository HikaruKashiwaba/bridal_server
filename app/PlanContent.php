<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanContent extends Model
{
    protected $table  = 'plan_content';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
