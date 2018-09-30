<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fair extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table       = 'fair';
    protected $guarded = ['id', 'delete_flg', 'create_date', 'update_date'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fairWeddingpark;
    protected $fairMynavi;
    protected $fairGurunavi;
    protected $fairRakuten;
    protected $fairZexy;
    protected $fairMinna;

    // protected $appends = ['fair_zexy'];
    // protected $appends = ['fair_weddingpark'];
    // protected $appends = ['fair_mynavi'];
    // protected $appends = ['fair_gurunavi'];
    // protected $appends = ['fair_rakuten'];
    // protected $appends = ['fair_minna'];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function image() {
        return $this->belongsTo('App\Image', 'image_id');
    }

    public function fairContent() {
	    return $this->hasMany('App\FairContent');
    }

    public function fairWeddingpark()
    {
        return $this->hasOne('App\FairWeddingpark');
    }

    public function fairMynavi()
    {
        return $this->hasOne('App\FairMynavi');
    }

    public function fairGurunavi()
    {
        return $this->hasOne('App\FairGurunavi');
    }

    public function fairRakuten()
    {
        return $this->hasOne('App\FairRakuten');
    }

    public function fairZexy()
    {
        return $this->hasOne('App\FairZexy');
    }

    public function fairMinna()
    {
        return $this->hasOne('App\FairMinna');
    }

    // public function getFairZexyAttribute() {
    //     return $this->attributes['fair_zexy'] == 'yes';
    // }
    // public function getFairWeddingParkAttribute() {
    //     return $this->attributes['fair_weddingpark'] == 'yes';
    // }
    // public function getFairMynaviAttribute() {
    //     return $this->attributes['fair_mynavi'] == 'yes';
    // }
    // public function getFairGurunaviAttribute() {
    //     return $this->attributes['fair_gurunavi'] == 'yes';
    // }
    // public function getFairRakutenAttribute() {
    //     return $this->attributes['fair_rakuten'] == 'yes';
    // }
    // public function getFairMinnaAttribute() {
    //     return $this->attributes['fair_minna'] == 'yes';
    // }
}
