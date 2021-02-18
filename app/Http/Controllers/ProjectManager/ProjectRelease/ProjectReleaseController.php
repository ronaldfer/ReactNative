<?php

namespace App\Http\Controllers\ProjectManager\ProjectRelease;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectRelease;
use App\Models\ProjectsPlans;
use App\Models\PalletsFileData;
use App\Models\ProjectSummary;
use App\Models\CustomerPortalMain;

class ProjectReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        /*status summary code*/
        $project_data           = Project::findOrfail($id);
        $project_release_list   = ProjectRelease::where('projects_id',$id)->get();
        $projects_plans         = ProjectsPlans::where('projects_id',$id)->get();
        $project_summary        = CustomerPortalMain::where('JobNumber',$project_data->job_number)->get();
        $palletsFileData        = PalletsFileData::select("pallets_file_data.*", \DB::raw("GROUP_CONCAT(CONCAT(pallets_file_data.StoneID, '=', pallets_file_data.Produced)) as finalStoneData"))->where('JobNumber',$project_data->job_number) ->groupBy("PalletNumber")->get();


        $co = [];
        $am = [];
        $rm = [];
        $mu = [];

        foreach ($project_summary as $project_summary_key => $project_summary_value) {
            $suffix = $project_summary_value->Suffix;

            if(str_contains($suffix,'CO')){
                $co[] = $project_summary_value;
                // echo "CO<br>";
            }
            elseif (str_contains($suffix,'AM')) {
                $am[] = $project_summary_value;
                // echo "AM<br>";
            }elseif (str_contains($suffix,'RM')) {
                $rm[] = $project_summary_value;
                // echo "RM<br>";
            }elseif (str_contains($suffix,'MU')) {
                $mu[] = $project_summary_value;
                // echo "RM<br>";
            }

        }
        $project_summary = array_merge($mu,$co,$am,$rm);

        return view('project-manager.projects.project-release.view-project-release',compact(['project_data','project_release_list','projects_plans','project_summary','palletsFileData']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
