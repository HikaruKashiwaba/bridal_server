<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FairContentPart extends Model {
    //
    protected $table   = 'fair_content_part';
    protected $guarded = ['id'];

    public function fairContentDetail() {
	    return $this->hasMany('App\FairContentDetail', 'fair_content_part_id');
    }

}
