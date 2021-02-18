<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use App\Models\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','company_id','email','address','image','contact','status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole(String $roleName)
    {
        /*die($roleName);*/
        // echo "hasroles";
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function roles()
    {
        // die('roles');1
        return $this->belongsToMany('App\Models\Role');
    }
    public function addRole(String $roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if ($role) $this->roles()->save($role);
    }
}
