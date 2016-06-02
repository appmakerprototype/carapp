<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'confirmed', 'api_key', 'key_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_key',
    ];

    public static function queryUser($email){
        return DB::select('select email, password from users where email = ?', [$email]);
    }

}
