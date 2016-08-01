<?php

namespace App;
use Eloquent;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;


//use Illuminate\Foundation\Auth\User as Authenticatable;

//class User extends Authenticatable
class User extends Eloquent implements Authenticatable, CanResetPassword
{
     use AuthenticableTrait;
     use CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile() {
        return $this->hasMany('App\Models\Profile');
    }
}
