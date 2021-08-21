<?php

namespace App\Models;


use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements JWTSubject  , MustVerifyEmail

{
    use HasFactory ;
    use Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'username',
        'password',
        'stack_status',
        'stack_main',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'timestamp',
        'created_at'=>'timestamp',
        'updated_at'=>'timestamp',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function analysis(){
        return $this->hasMany(Analysis::class);
    }

    public function post(){
        return $this->hasMany(Post::class);
    }

    public function regulation(){
        return $this->hasMany(Regulation::class);
    }

    public function image(){
        return $this->morphOne(Image::class , 'imageable' );
    }

    public function urequest(){
        return $this->hasOne(Urequest::class);
    }

    public function token(){
        return $this->hasOne(Token::class);
    }
    public function questions(){
        return $this->hasMany(StackQuestion::class);
    }

    public function answers(){
        return $this->hasMany(StackAnswer::class);
    }
}
