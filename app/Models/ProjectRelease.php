<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRelease extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'one_drive_projects_release_id','project_release_ver','projects_id','pm_id','company_id','release_url_link'
    ];
}
