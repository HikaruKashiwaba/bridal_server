<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'account';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $weddingpark;
    protected $zexy;
    protected $rakuten;
    protected $gurunavi;
    protected $mynavi;
    protected $minna;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
