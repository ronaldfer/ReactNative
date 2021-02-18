<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalletsFileData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'JobNumber','Suffix','ReleaseNumber','Released','Produced','Staged','Shipped','ReleasedValue',
    ];

}
