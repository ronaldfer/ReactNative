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
    private $base_url;

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
        $this->base_url = config('onedrive.one_drive.base_url');
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

        // create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken1);

        \Log::info(date('Y-m-d H:i:s').' Start Cron And Handle Function');
        // print_r(storage_path("app/pallets-file-data.csv"));
        /*upload projects*/
        $uploadProjects = $this->uploadProjects($accessToken1);

        /*get Pallets*/
        /*$data = $this->getPalletsFileData($accessToken1);*/
    }

    public function getAccessToken(){
        \Log::info(date('Y-m-d H:i:s').' Create Access Token And run getAccessToken function');
        $getRefreshToken    = OneDriveTokenManage::find(1)->get(['refreshToken']);
        $refreshToken       = $getRefreshToken[0]['refreshToken'];

        $endpoint = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

        $response = Http::asForm()->post($endpoint,[
                    "client_id"     =>config('onedrive.one_drive.clientId'),
                    "scope"         =>config('onedrive.one_drive.scopes'),
                    "refresh_token" =>$refreshToken,
                    "redirect_uri"  =>config('onedrive.one_drive.redirectUri'),
                    "grant_type"    =>"refresh_token",
                    "client_secret" =>config('onedrive.one_drive.clientSecret'),
                ]);

        $data = $response->json();

        $update_data = OneDriveTokenManage::findOrfail(1)->update([
          'accessToken' => $data['access_token'],
        ]);
        return $update_data;
    }

    /*upload projects list in database*/
    function uploadProjects($accessToken){
        \Log::info(date('Y-m-d H:i:s').' Upload Projects and Run upload projects functions');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($this->base_url.'me/drive/items/01O2KUZSHJYDH7TPXIEZCJ336WVHJPMDEE/children');


            $status = $response->status();

            $responseData = json_decode($response->body());

            if($status == 200){
                $onedrive_project_data = $responseData->value;

                /*empty pallets file data in first time run cron*/
                $csvData = [];
                $storageFile = Storage::put('pallets-file-data.csv', $csvData);

                foreach($onedrive_project_data as $key => $value){
                    $job_number = $value->name;

                    $folderName = $value->name;

                    $folderId   = $value->id;

                    $job_number_data = Project::where('job_number',$job_number)->exists();

                    if(!$job_number_data){
                        echo "<h1>projects if</h1>";

                        $data = Project::create([
                                'job_number'            =>  $job_number,
                                'job_name'              =>  $job_number,
                                'one_drive_projects_id' =>  $value->id
                            ]);
                        $data->save();
                        $project_db_id = $data['id'];

                        $putProjectsReleaseFolderId = $this->getProjectsReleaseFolderId($folderId,$accessToken,$folderName,$project_db_id);

                        $customerPortalSummary = $this->getCustomerPortalData($accessToken,$job_number);

                        // $putProjectsReleaseFolderId = $this->getProjectsSummary($folderId,$accessToken,$folderName,$project_db_id);

                        // $putProjectsPlansFolderId = $this->getProjectsPlansFolderId($folderId,$accessToken,$folderName,$project_db_id);
                        /*echo "if => ".$key;*//*
                        print_r($customerPortalSummary);
                        echo "</pre>";*/
                        // die();
                        // print_r($putProjectsReleaseFolderId);
                    }else{
                        $update_data = Project::where('job_number',$job_number)->update([
                            'job_name'              =>  $value->name,
                            'one_drive_projects_id' =>  $value->id
                        ]);

                        $projects_id = Project::where('job_number',$job_number)->get('id');

                        foreach ($projects_id as $project_id_key => $project_id_value) {
                            $putProjectsReleaseFolderId = $this->getProjectsReleaseFolderId($folderId,$accessToken,$folderName,$project_id_value->id);

                            $customerPortalSummary = $this->getCustomerPortalData($accessToken,$job_number);
                            // $putProjectsPlansFolderId = $this->getProjectsPlansFolderId($folderId,$accessToken,$folderName,$project_id_value->id);
                        }
                    }
                }
            }else{
                throw new \Exception('Failed');
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /*get release projects id*/
    function getProjectsReleaseFolderId($projectsReleaseFolderId,$accessToken,$foldername,$project_db_id){
        \Log::info(date('Y-m-d H:i:s').' Get Projects Release Folder Id and Run get projects release folder id functions');
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($this->base_url.'me/drive/items/'.$projectsReleaseFolderId.'/children');


            $status = $response->status();

            if($status == 200){
                $responseData = json_decode($response->body());
                $onedrive_projects_release_folder_id = $responseData->value;

                foreach ($onedrive_projects_release_folder_id as $key => $value) {
                    $job_number = $value->name;

                    $folderName = $value->name;

                    $folderId   = $value->id;
                    if($folderName == "Releases"){
                        $getRelease = $this->uploadProjectsReleases($accessToken,$folderId,$project_db_id);
                    }elseif($folderName == "Plans"){
                        $getPlans = $this->uploadProjectsPlans($accessToken,$folderId,$project_db_id);
                    }
                }
            }else{
                throw new \Exception('Failed');
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /*upload projects releases*/
    function uploadProjectsReleases($accessToken,$folderId,$project_db_id){
        \Log::info(date('Y-m-d H:i:s').' Upload Projects Release and Run Upload Projects Release functions');
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($this->base_url.'me/drive/items/'.$folderId.'/children');


            $status = $response->status();

            if($status == 200){
                $responseData = json_decode($response->body());
                $onedrive_projects_release_data = $responseData->value;

                foreach ($onedrive_projects_release_data as $key => $value) {

                    $project_release_ver = $value->name;

                    $job_number_data = ProjectRelease::where('project_release_ver',$project_release_ver)->exists();

                    if(!$job_number_data){

                        $getReleaseLink = $this->getRleaseFileLink($accessToken,$value->id);

                        $data = ProjectRelease::create([
                            'project_release_ver'           =>  $value->name,
                            'one_drive_projects_release_id' =>  $value->id,
                            'projects_id'                   =>  $project_db_id,
                            'release_url_link'              =>  $getReleaseLink
                        ]);
                    }else{
                        $job_number_data = ProjectRelease::where('project_release_ver',$project_release_ver)->get();

                        $getReleaseLink = $this->getRleaseFileLink($accessToken,$value->id);

                        $data = ProjectRelease::where('project_release_ver',$project_release_ver)->update([
                            'release_url_link'              =>  $getReleaseLink
                        ]);
                    }
                }
            }else{
                throw new \Exception('Failed');
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /*get file link*/
    public function getRleaseFileLink($accessToken,$releaseId){
        \Log::info(date('Y-m-d H:i:s').' Get Release File Link and Run Get Release File Link functions');
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->post($this->base_url.'me/drive/items/'.$releaseId.'/createLink');

            $status = $response->status();

            if($status == 200){
                $responseData = json_decode($response->body());

                $link = $responseData->link;

                foreach ($link as $key => $value) {
                    if($key == 'webUrl'){

                        // echo $value."<br>";
                        return $value;
                    }
                }
            }else{
                throw new \Exception('Failed');
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*upload project plans*/
    public function uploadProjectsPlans($accessToken,$folderId,$project_db_id){
        \Log::info(date('Y-m-d H:i:s').' Upload Projects Plans and Run Upload Projects Plans functions');
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($this->base_url.'me/drive/items/'.$folderId.'/children');

            $status = $response->status();

            if($status == 200){
                $responseData = json_decode($response->body());

                $project_plans_data = $responseData->value;

                foreach ($project_plans_data as $key => $value) {

                    $project_plan_ver = $value->name;

                    $job_number_data = ProjectsPlans::where('project_plan_ver',$project_plan_ver)->exists();
                    // echo "<h1> Hello </h1>";
                    // echo $job_number_data;
                    if(!$job_number_data){
                        echo "if";
                        $data = ProjectsPlans::create([
                            'project_plan_ver'           =>  $value->name,
                            'one_drive_projects_plan_id' =>  $value->id,
                            'projects_id'                =>  $project_db_id,
                            // 'plan_url_link'              =>  $getReleaseLink
                        ]);
                    }
                }
            }else{
                throw new \Exception('Failed');
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*get customer portal data*/
    function getCustomerPortalData($accessToken,$job_number){
        \Log::info(date('Y-m-d H:i:s').' GET EAsy Sales File data folder and Run Get easy sales data Plans functions');

        try {
            $api_url = $this->base_url.'/me/drive/root:/Easy_Sales_Data/'.$job_number.'-CustomerPortalMain.csv:/content';

            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($api_url);


            $status = $response->status();

            $responseData = $response->body();

            $csvData = $responseData;

            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
            // $storageFile = Storage::put()->append('pallets-file-data.csv', $csvData);
            $storageFile = Storage::append('pallets-file-data.csv', $csvData);

            if($status == 200){
                $response = $response->body();
                return $response;

            }else{
                // The server responded with some error. You can throw back your exception
                // to the calling function or decide to handle it here

                throw new \Exception('Failed');
            }

        }catch (\Exception $e) {
            //Catch the guzzle connection errors over here.These errors are something
            // like the connection failed or some other network error

            return $e->getMessage();

            $response = json_encode((string)$e->getResponse()->getBody());
        }

        // $response = Http::get('http://jsonplaceholder.typicode.com/posts');
        /*$response = Http::withHeaders([
            'Authorization' => "Bearer ".$accessToken
        ])->get($this->base_url.'/me/drive/root:/Easy_Sales_Data1/'.$job_number.'-CustomerPortalPallets.csv:/content');

        $status = $response->status();
        $responseData = $response->body();

        if($status != 200){
             $response = json_encode((string)$e->getResponse()->getBody());
             dd($response);
        }else{
            dd($responseData);
        }
        // dd($jsonData);*/
    }
    /*get customer portal pallets file*/
    public function getPalletsFileData($accessToken){
        $emptyData = PalletsFileData::truncate();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            /*CURLOPT_URL => 'https://graph.microsoft.com/v1.0//me/drive/items/01O2KUZSHAMNSTDTJFFNCYA4CFAM7VQSM2/content',
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/CustomerPortalMain.csv:/content',*/
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/drive/root:/Easy_Sales_Data/17705-CustomerPortalPallets.csv:/content',
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
            // dd($csvData);

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
            }

            $file_upload = Storage::put('CustomerPortalPallets.csv', $csvData);
            return $file_upload;
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
