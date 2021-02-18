<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use App\Models\OneDriveTokenManage;
use App\Models\Project;
use App\Models\ProjectRelease;
use App\Models\ProjectsPlans;
use App\Models\PalletsFileData;
use Http;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Storage;


class GetOneDriveProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetOneDriveProjects:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        \Log::info(date('Y-m-d H:i:s').' chal gya re baba');
        $token = $this->getAccessToken();
        $getAccessToken = OneDriveTokenManage::find(1)->get(['accessToken']);
        $accessToken1 = $getAccessToken[0]['accessToken'];
        /*set token cache*/
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        $file_name = 'users.xlsx';
        $media_path = storage_path('app/users.xlsx');
        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken1);

        /*upload projects*/
        $uploadProjects = $this->uploadProjects($accessToken1);

        /*get Pallets*/
        // $data = $this->getPalletsFileData($accessToken1);

        /*get projects plans*/
        \Log::info(' befor customer portal summary function');
        $data = $this->downloadPlletsFile($accessToken1);
        dd($data);
        \Log::info(' after customer portal summary function');
    }

    public function getAccessToken(){
        $getRefreshToken    = OneDriveTokenManage::find(1)->get(['refreshToken']);
        $refreshToken       = $getRefreshToken[0]['refreshToken'];
        // OneDriveTokenManage::
        $endpoint = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

        $response = Http::asForm()->post($endpoint,[
                    "client_id"=>config('app.one_drive.clientId'),
                    "scope"=>config('app.one_drive.scopes'),
                    "refresh_token"=>$refreshToken,
                    "redirect_uri"=>config('app.one_drive.redirectUri'),
                    "grant_type"=>"refresh_token",
                    "client_secret"=>config('app.one_drive.clientSecret'),
                ]);

        $data = $response->json();

        $update_data = OneDriveTokenManage::findOrfail(1)->update([
          'accessToken' => $data['access_token'],
        ]);
        return $update_data;
    }

    /*upload projects list in database*/
    function uploadProjects($accessToken){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/drive/items/01O2KUZSHJYDH7TPXIEZCJ336WVHJPMDEE/children",
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
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
          echo "<pre> cURL Error #:" . $err."</pre>";
        }else {
            \Log::info(date('Y-m-d H:i:s').' upload Projects');
            $data = json_decode($response);

            $count_data = $data->value;

            foreach ($count_data as $key => $value) {
                $job_number = $value->name;

                $folderName = $value->name;

                $folderId   = $value->id;

                $job_number_data = Project::where('job_number',$job_number)->exists();

                if(!$job_number_data){
                    $data = Project::create([
                                'job_number'            =>  $job_number,
                                'job_name'              =>  $job_number,
                                'one_drive_projects_id' =>  $value->id
                            ]);
                    $data->save();
                    $project_db_id = $data['id'];

                    $putProjectsReleaseFolderId = $this->getProjectsReleaseFolderId($folderId,$accessToken,$folderName,$project_db_id);

                    // $putProjectsPlansFolderId = $this->getProjectsPlansFolderId($folderId,$accessToken,$folderName,$project_db_id);
                    echo "<h1>if</h1><pre>";
                    print_r($putProjectsReleaseFolderId);
                    echo "</pre>";
                    // print_r($putProjectsReleaseFolderId);
                }else{
                    $update_data = Project::where('job_number',$job_number)->update([
                        'job_name'              =>  $value->name,
                        'one_drive_projects_id' =>  $value->id
                    ]);

                    $projects_id = Project::where('job_number',$job_number)->get('id');

                    foreach ($projects_id as $project_id_key => $project_id_value) {
                        $putProjectsReleaseFolderId = $this->getProjectsReleaseFolderId($folderId,$accessToken,$folderName,$project_id_value->id);
                        // $putProjectsPlansFolderId = $this->getProjectsPlansFolderId($folderId,$accessToken,$folderName,$project_id_value->id);

                    }
                    /*echo "<h1>else</h1><pre>";
                    print_r($putProjectsReleaseFolderId);
                    echo "</pre>";*/
                    // $putProjectsReleaseFolderId = $this->getProjectsReleaseFolderId($folderId,$accessToken,$folderName,$projects_id);

                    // print_r($putProjectsReleaseFolderId);
                }
            }
        }
    }
    /*get release projects id*/
    function getProjectsReleaseFolderId($projectsReleaseFolderId,$accessToken,$foldername,$project_db_id){

       $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/drive/items/".$projectsReleaseFolderId."/children",
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
          echo "<pre> cURL Error #:" . $err."</pre>";
        } else {
            \Log::info(date('Y-m-d H:i:s').' get Projects Release Folder Id');
            $data = json_decode($response);

            $count_data = $data->value;

            foreach ($count_data as $key => $value) {
                $job_number = $value->name;

                $folderName = $value->name;

                $folderId   = $value->id;
                if($folderName == "Releases"){
                    $getRelease = $this->uploadProjectsReleases($accessToken,$folderId,$project_db_id);
                }elseif($folderName == "Plans"){
                    $getPlans = $this->uploadProjectsPlans($accessToken,$folderId,$project_db_id);
                }
            }
        }
    }


    /*upload projects releases*/
    function uploadProjectsReleases($accessToken,$folderId,$project_db_id){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/drive/items/".$folderId."/children",
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
          echo "<pre> cURL Error #:" . $err."</pre>";
        } else {
            \Log::info(date('Y-m-d H:i:s').' upload Projects Releases');
            $data = json_decode($response);

            $count_data = $data->value;

            foreach ($count_data as $key => $value) {
                $project_release_ver = $value->name;

                $job_number_data = ProjectRelease::where('project_release_ver',$project_release_ver)->exists();

                if(!$job_number_data){
                    echo "if";
                    $getReleaseLink = $this->getRleaseFileLink($accessToken,$value->id);
                    print_r($getReleaseLink);

                    $data = ProjectRelease::create([
                        'project_release_ver'           =>  $value->name,
                        'one_drive_projects_release_id' =>  $value->id,
                        'projects_id'                   =>  $project_db_id,
                        'release_url_link'              =>  $getReleaseLink
                    ]);
                }
            }
        }
    }
    /*get file link*/
    public function getRleaseFileLink($accessToken,$releaseId){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/drive/items/".$releaseId."/createLink",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\n    \"type\" : \"embed\",\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        echo "<h2> Link </h2><pre>";
        print_r($response);
        echo "</pre>";
        dd("ankit");
        if($err){
            echo "<pre> cURL Error #:" . $err."</pre>";
        }else{
            $data = json_decode($response);
            $link = $data->link;
           // $job_number_data = ProjectRelease::where('project_release_ver',$project_release_ver)->exists();

            foreach ($link as $key => $value) {
                if($key == 'webUrl'){
                    return $value;
                }

                // if($value=='https://onedrive.live.com/embed?resid=196797B1CBE6FB11%211653&authkey=!ABfxNSAxSKAGpEE&em=2')
                // echo "working";
            }
        }
    }

    /*upload project plans*/
    public function uploadProjectsPlans($accessToken,$folderId,$project_db_id){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/items/'.$folderId.'/children',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$accessToken
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "<pre> cURL Error #:" . $err."</pre>";
        } else {
            \Log::info(date('Y-m-d H:i:s').' upload Projects Plans');
            $data = json_decode($response);

            $count_data = $data->value;

            foreach ($count_data as $key => $value) {
                $project_plan_ver = $value->name;
                $job_number_data = ProjectsPlans::where('project_plan_ver',$project_plan_ver)->exists();
                // echo "<h1> Hello </h1>";
                // echo $job_number_data;
                if(!$job_number_data){
                    $data = ProjectsPlans::create([
                        'project_plan_ver'           =>  $value->name,
                        'one_drive_projects_plan_id' =>  $value->id,
                        'projects_id'                =>  $project_db_id,
                        // 'plan_url_link'              =>  $getReleaseLink
                    ]);
                }
            }
        }
    }

    /*get pallets file*/
    public function getPalletsFileData($accessToken){
        $emptyData = PalletsFileData::truncate();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            /*CURLOPT_URL => 'https://graph.microsoft.com/v1.0//me/drive/items/01O2KUZSHAMNSTDTJFFNCYA4CFAM7VQSM2/content',
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/CustomerPortalMain.csv:/content',*/
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/CustomerPortalPallets.csv:/content',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err){
            echo "<pre> cURL Error #:" . $err."</pre>";
        }else{
            $csvData = $response;

            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));

            $header = NULL;
            $array = array();
           /* $k = 0;*/
            foreach ($data as $row) {
                if(!$header){
                    $header = $row;
                }else{
                    if(count($header) != count($row)){
                        if(count($header) > count($row)){
                            $i = count($row);
                            $j = count($header);
                            while ($i < $j) {
                                $row[] = "";
                                $i++;

                            }
                        }elseif(!empty($row['JobNumber'])){
                            echo "else<br>";
                            $i = count($header);
                            $j = 0;
                            while ($j < count($row)) {
                                if($j >= $i){
                                    unset($row[$j]);
                                }
                                $j++;

                            }
                        }
                    }
                    $array[] = array_combine($header, $row);
                }

                /*$k++;

                if($k > 100){
                    break;
                }*/
            }
            // $file_upload = Storage::put('file.csv', trim(json_encode($data)));
            $file_upload = Storage::put('pallets-file-data.csv', $csvData);
            dd($file_upload);
            /*
            echo "<h1> pallets datas </h1><pre>";
            print_r($array); echo "</pre>";*/
                    // print_r($header); echo "<br>";
            /*foreach ($data as $row) {
                if(!$header){
                    echo "";
                    $header = $row;
                }else{
                    if(count($header) != count($row)){
                        if(count($header) > count($row)){
                            $i = count($row);
                            $j = count($header);
                            while ($i < $j) {
                                $row[] = "";
                                $i++;
                            }
                        }else{
                            $i = count($header);
                            $j = 0;
                            while ($j < count($row)) {
                                if($j >= $i){
                                    unset($row[$j]);
                                }
                                $j++;
                            }
                        }
                    }
                    $array[] = array_combine($header, $row);
                }
            }
            /*ini_set('max_execution_time', -1);

            */
            // dd($array);
            /*$tableData = DB::table('pallets_file_data')->insert($array);

            if($tableData){
                return true;
            }else{
                return false;
            }*/
        }
    }

    /*get projects summary file from ondrive filename =>*/
    public function getProjectsSummaryData($accessToken){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/CustomerPortalMain.csv:/content',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err){
            echo "<pre> cURL Error #:" . $err."</pre>";
        }else{
            $csvData = $response;

            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
            $header = NULL;
            $array = array();

            foreach ($data as $row) {
                if(empty($row[0])){
                    continue;
                }
                if(!$header){
                    $header = $row;
                }else{
                    if(count($header) != count($row)){
                        if(count($header) > count($row)){
                            $i = count($row);
                            $j = count($header);
                            while ($i < $j) {
                                $row[] = "";
                                $i++;

                            }
                        }elseif(!empty($row['JobNumber'])){
                            $i = count($header);
                            $j = 0;
                            while ($j < count($row)) {
                                if($j >= $i){
                                    unset($row[$j]);
                                }
                                $j++;

                            }
                        }
                    }
                    $array[] = array_combine($header, $row);
                }
            }
            $tableData = DB::table('project_summaries')->insert($array);
            return $tableData;
        }
    }

    /*get customer portal summary data*/
    public function getPortalSummaryData($accessToken){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/CustomerPortalSummary.csv:/content',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err){
            echo "<pre> cURL Error #:" . $err."</pre>";
        }else{
            $csvData = $response;

            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
            $header = NULL;
            $array = array();

            foreach ($data as $row) {
                if(empty($row[0])){
                    continue;
                }
                if(!$header){
                    $header = $row;
                }else{
                    if(count($header) != count($row)){
                        if(count($header) > count($row)){
                            $i = count($row);
                            $j = count($header);
                            while ($i < $j) {
                                $row[] = "";
                                $i++;

                            }
                        }elseif(!empty($row['JobNumber'])){
                            $i = count($header);
                            $j = 0;
                            while ($j < count($row)) {
                                if($j >= $i){
                                    unset($row[$j]);
                                }
                                $j++;

                            }
                        }
                    }
                    $array[] = array_combine($header, $row);
                }
            }
            dd($array);
            /*
            $tableData = DB::table('project_summaries')->insert($array);
            return $tableData;*/
        }

    }

    /*download pallets file /me/drive/root:/{item-path}:/content*/
    public function downloadPlletsFile($accessToken){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/CustomerPortalPallets.csv:/content',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$accessToken
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "<pre> cURL Error #:" . $err."</pre>";
        } else {
            \Log::info(date('Y-m-d H:i:s').' upload Projects Plans');

            $csvData = $response;

            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
            $storageFile = Storage::put('pallets-file-data.csv', $csvData);
            return $storageFile;
        }
    }
}
