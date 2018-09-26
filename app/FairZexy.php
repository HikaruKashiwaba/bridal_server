<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FairZexy extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'fair_zexy';
    protected $fairContent;
    protected $primaryKey = 'fair_id';
    protected $guarded = ['fair_id'];
    //protected $guarded = ['id', 'delete_flg', 'create_date', 'update_date'];
    //const CREATED_AT = 'created_at';
    //const UPDATED_AT = 'updated_at';

    public function fair()
    {
        return $this->belongsTo('App\Fair');
    }
}
