<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\PalletsFileData;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_name','pm_id','company_id','state','city','job_number','one_drive_projects_id',
    ];

    public function getPalletNumber(String $jobNumber)
    {
        dd($this->belongsToMany('App\Models\PalletsFileData'));
        /*die($roleName);*/
        // echo "hasroles";/*/*/*
        // ""
       /*$groupedSalesCampaign = "Select * from pallets_file_data Where 'JobNumber' = '17705' GROUP BY 	StoneID";
$groupedSalesCampaign = PalletsFileData::where('JobNumber','17705')
            ->groupBy('StoneID','Produced')
            ->toSql('StoneID','Produced');

        dd($groupedSalesCampaign);
        dd($jobNumber);
        return $this->roles()->where('name', $roleName)->exists();*/
    }

}
