<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsReleaseNotes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'projects_release_notes','projects_id','pm_id','projects_release_id','company_id'
    ];
}
