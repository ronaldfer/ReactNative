<?php

namespace App\Http\Controllers\Admin\ProjectsRelease;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectRelease;
use App\Models\Project;
use App\User;
use App\Models\OneDriveTokenManage;
use App\TokenStore\TokenCache;
use File;
use \PDF;

class ProjectsReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req,$id)
    {
        $pr_data = ProjectRelease::where('projects_id',$id)->get();
        // $project_data = [];
        // $pm_data = [];
        $project_data = Project::findOrfail($id);
        $pm_data = [];
        /*if(!$pr_data->isEmpty()){
            $pm_data[] = User::findOrfail($pr_data[0]['pm_id']);
        }*/
        return view('admin.projects.projects-releases.view-projects-release',compact('pr_data','project_data','pm_data'));

    }
    /**
     * Show the form for creating a new resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.projects-releases.create-projects-release');
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
        $data = ProjectRelease::findOrfail($id);
        dd($data);
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

    /*downlaod Release*/
    public function downlaodRelease(Request $req,$id){
        $getAccessToken = OneDriveTokenManage::find(1)->get(['accessToken']);

        $accessToken = $getAccessToken[0]['accessToken'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/drive/items/".$id."/content",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "Error =>".$err;
            // return "cURL Error #:" . $err;
        } else {

            $public_dir=public_path().'/assets/release-upload';
            $fileName = date('YmdHis').'.pdf';
            $dataFile = $public_dir."/".$fileName;

            $view = file_put_contents($dataFile, $response);

            if (file_exists($public_dir.'/'.$fileName))
            {
                header('Content-Type: application/pdf');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($public_dir.'/'.$fileName);
                unlink($public_dir.'/'.$fileName);
                exit;
            }
        }
    }
}
