<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSummary extends Model
{
    protected $fillable = ['JobNumber','Suffix','ReleaseNumber','Released','Produced','Staged','Shipped','ReleasedValue'
	];
}
