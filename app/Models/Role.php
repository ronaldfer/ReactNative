<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   	public const Admin = 'Admin';
    public const Staff = 'Staff';
    public const ProjectManager = 'ProjectManager';
    public const Company = 'Company';

    /*user models*/
    public function users(){
        return $this->belongsToMany('App\User')->using('App\Models\RoleUser');
    }

    /*company models*/
    public function company(){
        return $this->belongsToMany('App\Models\Company')->using('App\Models\RoleUser');
    }
    public function user(){
	    return $this->belongsTo(User::class);
	}
}
