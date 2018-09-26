<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FairGurunavi extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'fair_gurunavi';
    protected $fairContent;
    protected $primaryKey = 'fair_id';
    protected $guarded = ['fair_id'];
    //protected $guarded = ['id', 'delete_flg', 'create_date', 'update_date'];
    //const CREATED_AT = 'create_date';
    //const UPDATED_AT = 'update_date';
    //const CREATED_AT = 'create_dt';
    //const UPDATED_AT = 'update_dt';

    public function fair()
    {
        return $this->belongsTo('App\Fair');
    }
}
