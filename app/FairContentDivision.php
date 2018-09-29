<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FairContentDivision extends Model
{
    //
    protected $table   = 'fair_content_division';
    protected $guarded = ['id'];

    public function fairContentDetail() {
        return $this->hasMany('App\FairContentDetail', 'fair_content_division_id');
    }
}
