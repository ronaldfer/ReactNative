<?php

namespace App\Http\Controllers\ProjectManager\ProjectReleaseNotes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectRelease;
use App\Models\ProjectsReleaseNotes;
use App\Models\ProjectsPlans;
use App\Models\CustomerPortalMain;
use App\Models\ProjectSummary;

class ProjectReleaseNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id){
        $getReleaseNumber = $id;
        $getReleaseNumber = explode("-", $id);

        $suffix = $getReleaseNumber[1];
        $releaseNumber = $getReleaseNumber[0];
        $jobNumber = $getReleaseNumber[2];

        $release_number = $releaseNumber.'-'.$suffix;


        $projects_summary = ProjectSummary::where([
                                ["Suffix",'=',$suffix],
                                ['ReleaseNumber', '=',$releaseNumber],
                                ['JobNumber', '=',$jobNumber],
                            ])->get();

        $project_data = Project::where('job_number',$jobNumber)->get();

        $project_release_data = ProjectRelease::where('projects_id',$project_data[0]['id'])->get();
        // dd($project_release_data);

        // dd($project_release_data);

        // dd($pr)

        // $projects_customer_main = CustomerPortalMain::where('ReleaseNumber',$id)->get();
        // dd($project_release_data);

        $notes_data = ProjectsReleaseNotes::where('projects_release_id',$id)->get();
        // $notes_data = [];

        return view('project-manager.projects.project-release-notes.view-project-release-note',compact(['notes_data','project_release_data','project_data','release_number','projects_summary','id']));
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

    public function viewPlans($id){
        $projectsPlansData = ProjectsPlans::find($id);
        $project_data = Project::findOrfail($projectsPlansData->projects_id);

        return view('project-manager.projects.project-plans.view-project-plans-note',compact(['projectsPlansData','project_data']));
    }
}
