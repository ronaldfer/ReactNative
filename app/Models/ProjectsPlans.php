<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsPlans extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'one_drive_projects_plan_id','project_plan_ver','plan_url_link','projects_id','pm_id','company_id'
    ];
}
