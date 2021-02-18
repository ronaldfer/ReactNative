<?php

namespace App\Http\Controllers\Admin\ProjectsRelease\ProjectReleaseNotes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectsReleaseNotes;
use App\Models\ProjectRelease;
use App\Models\Project;
class ProjectsReleaseNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $project_release_data = ProjectRelease::findOrfail($id);
        $project_data = Project::findOrfail($project_release_data->projects_id);
        $notes_data = ProjectsReleaseNotes::where('projects_release_id',$id)->get();

        return view('admin.projects.projects-release-notes.view-projects-release-notes',compact(['notes_data','project_release_data','project_data']));
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
        // dd($request->projects_release_projects_id);
        $notes_data = ProjectsReleaseNotes::create([
            'projects_release_notes'    =>  $request->notes_data,
            /*'projects_id'               =>  $request->projects_release_projects_id,
            'pm_id'                     =>  $request->projects_release_pm_id,*/
            'projects_release_id'       =>  $request->projects_release_id,
            /*'company_id'                =>  $request->projects_release_company_id,*/
        ]);
        $notes_data->save();
        if($notes_data){
            return response()->json(['message'=>'Notes added successfully.'],200);
        }
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
