<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FairContent extends Model
{
    protected $dates = ['deleted_at'];
    protected $table       = 'fair_content';
    protected $guarded = ['id', 'delete_flg', 'create_date', 'update_date'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function fair()
    {
        return $this->belongsTo('App\Fair');
    }

    public function image1() {
        return $this->belongsTo('App\Image', 'image_id');
    }

    public function image2() {
        return $this->belongsTo('App\Image', 'image_id2');
    }

    public function image3() {
        return $this->belongsTo('App\Image', 'image_id3');
    }

    public function fairContentDivision() {
        return $this->hasMany('App\FairContentDivision', 'fair_content_id');
    }

    public function fairContentDetail() {
        return $this->hasMany('App\FairContentDetail', 'fair_content_id');
    }
}
