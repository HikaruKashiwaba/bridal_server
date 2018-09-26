<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'image';
    protected $guarded = ['id'];


    public function fairs() {
      return $this->hasMany('App\Fair', 'image_id');
    }

    public function fairContents() {
	    return $this->hasMany('App\FairContent', 'image_id');
    }
    public function fairContents2() {
	    return $this->hasMany('App\FairContent', 'image_id2');
    }
    public function fairContents3() {
	    return $this->hasMany('App\FairContent', 'image_id3');
    }
}
