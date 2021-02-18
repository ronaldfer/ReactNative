<?php
namespace App\Helpers;
use App\User;

class ProjectManagerHelper
{
	public static function getProjectManager()
    {
    	$data =  User::whereHas('roles', function($role) {
            $role->where('name', '=','ProjectManager');
        })->get()->toArray();
        return json_encode($data);
    }
}