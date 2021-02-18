<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Exports\ProjectsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\OneDriveTokenManage;
use App\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\User;
use Response;
use Http;
use File;

class ProjectController extends Controller
{
    private $base_url;
    public function __construct()
    {
        $this->base_url = config('onedrive.one_drive.base_url');
        // parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $projects_data = Project::paginate(10);

        $pm_data =  User::whereHas('roles', function($role) {
            $role->where('name', '=','ProjectManager');
        })->get();

        $company_data =  Company::all();

        return view('admin.projects.view-projects-details',compact(['projects_data','pm_data','company_data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.projects.create-projects');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request){

      $getAccessToken = OneDriveTokenManage::find(1)->get(['accessToken']);
      $accessToken    = $getAccessToken[0]['accessToken'];

      $data = ["name"=> $request->input("job_number"),"folder"=>(object)[],"@microsoft.graph.conflictBehavior"=> "rename"];


      Validator::make($request->all(), [
          'project_name'      => ['required', 'string', 'max:255'],
          'job_number'        => ['required', 'string', 'max:255'],
          'company_name'      => ['required', 'string', 'max:255'],
          'project_state'     => ['required', 'string', 'max:255'],
          'project_city'      => ['required', 'string', 'max:255'],
      ],[
          'project_name.required' => 'Project name is required.',
          'job-number.required'   => 'Project number is required.',
          'company_name.required' => 'Company name is required.',
          'project_state.required'=> 'Projects state is required.',
          'project_city.required' => 'Projects city is required.',
      ])->validate();

      /*$project_data = Project::create([
          'job_name'          =>  $request->input('project_name'),
          'job_number'        =>  $request->input('job_number'),
          'company_id'        =>  $request->input('company_name'),
          'pm_id'             =>  json_encode($request->pm_name),
          'city'              =>  $request->input('project_city'),
          'state'             =>  $request->input('project_state'),
      ]);*/

        // $project_data->save();

        /*echo json_encode($data);
        dd("ankit");*/
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0//me/drive/items/01O2KUZSHJYDH7TPXIEZCJ336WVHJPMDEE/children',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$accessToken,
          'Content-Type: application/json'
        ),
      ));

      $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo $httpcode;
        curl_close($curl);

        if ($err) {
          echo "<pre> cURL Error #:" . $err."</pre>";
        }else {
          $responseData = json_decode($response);

          /*CREATE PROJECTS IN DB*/
          $project_data = Project::create([
              'job_name'              =>  $request->input('project_name'),
              'job_number'            =>  $request->input('job_number'),
              'company_id'            =>  $request->input('company_name'),
              'pm_id'                 =>  json_encode($request->pm_name),
              'city'                  =>  $request->input('project_city'),
              'state'                 =>  $request->input('project_state'),
              'one_drive_projects_id' =>  $responseData->id
          ]);
          $project_data->save();
          /*$updateProjectFolderId = Project::find($project_data->id)->update([
              'one_drive_projects_id' =>  $responseData->id
          ]);*/
          if($httpcode == 201){

            $request->session()->flash('success', 'Project successfully created.');

            return redirect()->route('admin.all-projects');
          }else{
            $request->session()->flash('status', 'Project not created. Please check.');

            return redirect()->route('admin.all-projects');
          }
        }
      }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $projects_data  = Project::findOrfail($id);
        $pm_data        = json_decode($projects_data->pm_id);

        $pm_name_data = [];
        if($pm_data != null){
          foreach ($pm_data as $pm_datakey => $pm_datavalue) {
            $pm_name_data[] = User::find($pm_datavalue,['first_name','last_name']);
          }
        }else{
          $pm_name_data = "--";
        }

        $company_data   = Company::find($projects_data->company_id,['company_name']);

        if($projects_data){
            return response()->json(['projects_data'=>$projects_data,'pm_data'=>$pm_name_data,'company_data'=>$company_data],200);
        }else{
            return response()->json(['pm_data'=>'Projects not found.'],403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $project_data = Project::findOrfail($id);

        return view('admin.projects.edit-projects',compact('project_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        $project_id = $request->input('project_id');
        Validator::make($request->all(), [
            'project_name'      => ['required', 'string', 'max:255'],
            'job_number'        => ['required', 'string', 'max:255'],
            'company_name'      => ['required', 'string', 'max:255'],
            'project_state'     => ['required', 'string', 'max:255'],
            'project_city'      => ['required', 'string', 'max:255'],
        ])->validate();

        $project_data = Project::find($project_id)->update([
            'job_name'          =>  $request->input('project_name'),
            'job_number'        =>  $request->input('job_number'),
            'pm_id'             =>  $request->pm_name,
            'company_id'        =>  $request->input('company_name'),
            'city'              =>  $request->input('project_city'),
            'state'             =>  $request->input('project_state'),
            'total_pics'        =>  $request->input('project_pcs'),
            'made'              =>  $request->input('project_made'),
            'staged'            =>  $request->input('project_stage'),
            'shipped'           =>  $request->input('project_shipped'),
            'AAS_complete_date' =>  $request->input('project_complete_date'),
            'location'          =>  $request->input('project_location')
        ]);
        $request->session()->flash('success', 'Projects successfully updated.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){
      /*get access token*/
      $getAccessToken = OneDriveTokenManage::find(1)->get(['accessToken']);
      $accessToken    = $getAccessToken[0]['accessToken'];

      /*get onedrive projects folder id*/
      $getProjectData = Project::findOrfail($id);
      $projectFolderId = $getProjectData->one_drive_projects_id;

      /*delete folder in the one drive easy sales data*/
      try{
        $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->delete($this->base_url.'me/drive/items/'.$projectFolderId);

        $status = $response->status();

        if($status == 204){
          // $responseData = json_decode($response);

          $project_data = Project::find($id)->delete($id);

          $request->session()->flash('success', 'Project successfully deleted.');
          return back();
        }else{
            //throw new \Exception('Failed');
          $request->session()->flash('status', 'Project not deleted.');
          return back();
        }
      }catch (\Exception $e) {
          return $e->getMessage();
      }
    }

    public function export(Request $req){
        $file_name = 'Job Numbers.csv';
        $file =  Excel::store(new ProjectsExport, $file_name);
        if($file){
            $getAccessToken = OneDriveTokenManage::find(1)->get(['accessToken']);

            $accessToken1 = $getAccessToken[0]['accessToken'];

            $tokenCache = new TokenCache();
            $accessToken = $tokenCache->getAccessToken();


            $media_path = storage_path('app/'.$file_name);

            // Create a Graph client
            $graph = new Graph();
            $graph->setAccessToken($accessToken1);

            $file_data = fopen ($media_path, 'rb');
            $size=filesize ($media_path);
            $file_contents= fread ($file_data, $size);
            // dd($file_contents);
            \Log::info(date('Y-m-d H:i:s').$file_contents);

            fclose ($file_data);
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/drive/items/01O2KUZSBXLLVGB73SO5BIS3VWRZULJDBE/content",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "PUT",
              CURLOPT_POSTFIELDS => $file_contents,
              CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken1
              ),
            ));
            $response = curl_exec($curl);

            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
              return "cURL Error #:" . $err;
            } else {

              $req->session()->flash('success', 'File successfully exported on one drive account, Please check.');
              $check = File::Delete(storage_path('app/'.$file_name));
              return redirect()->back();
            }
        }
    }

    public function checkJobnumber(Request $request){
        $job_number = $request->job_number;

        $data = Project::where('job_number',$job_number)->exists();
        if($data){
            return "false";
        }else{
            return "true";
        }
    }



}

