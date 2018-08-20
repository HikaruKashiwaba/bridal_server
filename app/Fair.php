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
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
    public $fairContent = [];
    public $fairWeddingpark;
    public $fairMynavi;
    public $fairGurunavi;
    public $fairRakuten;
    public $fairZexy;
    public $fairMinna;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function fairContents() {
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
}
