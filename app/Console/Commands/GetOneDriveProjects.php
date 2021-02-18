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
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


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
    protected $description = 'Get one drive file data upload in mysql';

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

        /*upload projects*/
        $uploadProjects = $this->uploadProjects($accessToken1);
        // $getCustomerPortalMainData = $this->getCustomerPortalMainData($accessToken1,19364);
        // dd($getCustomerPortalMainData);
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

        $getProjects = Project::all();
        $csvData1   = [];
        $check1      = 0;
        $oldData1   = [];

        $csvData2   = [];
        $check2     = 0;
        $oldData2   = [];

        $csvData3   = [];
        $check3     = 0;
        $oldData3   = [];

        $filename1      = storage_path()."/app/CustomerPortalMain.csv";
        $oldFileData1   = fopen($filename1,"r");

        while(! feof($oldFileData1)){
            $oldData1[] = fgetcsv($oldFileData1);
        }

        fclose($oldFileData1);

        if(isset($oldData1[0])){
            unset($oldData1[0]);
            $oldData1 = array_values($oldData1);
        }

        $filename2      = storage_path()."/app/CustomerPortalPallets.csv";
        $oldFileData2   = fopen($filename2,"r");

        while(! feof($oldFileData2)){
            $oldData2[] = fgetcsv($oldFileData2);
        }

        fclose($oldFileData2);

        if(isset($oldData2[0])){
            unset($oldData2[0]);
            $oldData2 = array_values($oldData2);
        }

        $filename3      = storage_path()."/app/CustomerPortalSummary.csv";
        $oldFileData3   = fopen($filename3,"r");

        while(! feof($oldFileData3)){
            $oldData3[] = fgetcsv($oldFileData3);
            $oldData3 = array_values($oldData3);
        }

        fclose($oldFileData3);

        if(isset($oldData3[0])){
            unset($oldData3[0]);
        }

        foreach ($getProjects as $getProjectsKey => $getProjectsValue) {
            $job_number     =   $getProjectsValue->job_number;
            $folderId       =   $getProjectsValue->one_drive_projects_id;
            $project_db_id  =   $getProjectsValue->id;

            $customerPortalMainData  = $this->getCustomerPortalMainData($accessToken,$job_number);
            /*append data customer Portal Main Data file*/
            if($customerPortalMainData['status']){
                foreach ($customerPortalMainData['response'] as $dataValue) {
                    if($check1 && $dataValue[0] == "JobNumber" ){
                    }elseif($check1 && empty($dataValue[0]) ){
                    }elseif(!$check1 && $dataValue[0] == "JobNumber"){
                        $csvData1[] = $dataValue;
                        $check1 ++;
                    }else{
                        $csvData1[] = $dataValue;
                        if(isset($oldData1[0])){
                            foreach ($oldData1 as $oldKey => $oldDataValue) {
                                if(trim($oldDataValue[0]) == trim($dataValue[0])){
                                    unset($oldData1[$oldKey]);
                                }
                            }
                        }
                    }
                }
            }

            $customerPortalPallets  = $this->getCustomerPortalPalletsFileData($accessToken,$job_number);
            /*append data in customer portal pallets*/
            if($customerPortalPallets['status']){
                foreach ($customerPortalPallets['response'] as $dataValue) {
                    if($check2 && $dataValue[0] == "JobNumber" ){
                    }elseif($check2 && empty($dataValue[0]) ){
                    }elseif(!$check2 && $dataValue[0] == "JobNumber"){
                        $csvData2[] = $dataValue;
                        $check2 ++;
                    }else{
                        $csvData2[] = $dataValue;
                        if(isset($oldData2[0])){
                            foreach ($oldData2 as $oldKey => $oldDataValue) {
                                if(trim($oldDataValue[0]) == trim($dataValue[0])){
                                    unset($oldData2[$oldKey]);
                                }
                            }
                        }
                    }
                }
            }

            $customerPortalSummary  = $this->getCustomerPortalSummary($accessToken,$job_number);
            /*append data in  customer portal summary*/
            if($customerPortalSummary['status']){
                foreach ($customerPortalSummary['response'] as $dataValue) {
                    if($check3 && $dataValue[0] == "JobNumber" ){
                    }elseif($check3 && empty($dataValue[0]) ){
                    }elseif(!$check3 && $dataValue[0] == "JobNumber"){
                        $csvData3[] = $dataValue;
                        $check3 ++;
                    }else{
                        $csvData3[] = $dataValue;
                        if(isset($oldData3[0])){
                            foreach ($oldData3 as $oldKey => $oldDataValue) {
                                if(trim($oldDataValue[0]) == trim($dataValue[0])){
                                    unset($oldData3[$oldKey]);
                                }
                            }
                        }
                    }
                }
            }

        }

        $oldData1 = array_values($oldData1);
        $oldData2 = array_values($oldData2);
        $oldData3 = array_values($oldData3);

        foreach ($oldData1 as $value) {
            if(isset($value[0]) && !empty($value[0]))
                $csvData1[] = $value;
        }

        foreach ($oldData2 as $value) {
            if(isset($value[0]) && !empty($value[0]))
                $csvData2[] = $value;
        }

        foreach ($oldData3 as $value) {
            if(isset($value[0]) && !empty($value[0]))
                $csvData3[] = $value;
        }


        print_r($oldData1);
        echo "first <br>";
        print_r($oldData2);
        echo "second <br>";
        print_r($oldData3);

        die();


        $file = fopen($filename1,"wa+");
        foreach ($csvData1 as $line) {
          fputcsv($file, $line);
        }

        fclose($file);


        $file = fopen($filename2,"wa+");
        foreach ($csvData2 as $line) {
          fputcsv($file, $line);
        }

        fclose($file);

        $file = fopen($filename3,"wa+");
        foreach ($csvData3 as $line) {
          fputcsv($file, $line);
        }

        fclose($file);

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

    /*get customer portal main data*/
    function getCustomerPortalMainData($accessToken,$job_number){
        \Log::info(date('Y-m-d H:i:s').' GET customer portal main data folder and Run get Customer Portal Main Data functions');
        // echo "yes => <br>";
        try {
            $api_url = $this->base_url.'/me/drive/root:/Easy_Sales_Data/'.$job_number.'-CustomerPortalMain.csv:/content';

            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($api_url);


            $status = $response->status();

            $responseData = $response->body();

            $csvData = $responseData;


            if($status == 200){
                $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
                return array("status" => 1, "response" => $data);

            }else{
                return array("status" => 0);
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
    }

    /*get customer portal pallets file*/
    public function getCustomerPortalPalletsFileData($accessToken,$job_number){
        \Log::info(date('Y-m-d H:i:s').' GET customer portal pallets data folder and Run get Customer Portal Pallets File Data functions');

        // $emptyData = PalletsFileData::truncate();
        try {
            $api_url = $this->base_url.'/me/drive/root:/Easy_Sales_Data/'.$job_number.'-CustomerPortalPallets.csv:/content';

            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($api_url);


            $status = $response->status();

            $responseData = $response->body();

            $csvData = $responseData;

            /*$data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));

            $storageFile = Storage::append('CustomerPortalPallets.csv', $csvData);*/

            if($status == 200){
                $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
                return array("status" => 1, "response" => $data);
                /*
                $response = $response->body();
                return $response;*/

            }else{
                return array("status" => 0);
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
    }

    /*get customer portal summary data*/
    public function getCustomerPortalSummary($accessToken,$job_number){
        \Log::info(date('Y-m-d H:i:s').' GET customer portal summary data folder and Run get Customer Portal Summary Data functions');

        try {
            $api_url = $this->base_url.'/me/drive/root:/Easy_Sales_Data/'.$job_number.'-CustomerPortalSummary.csv:/content';

            $response = Http::withHeaders([
                'Authorization' => "Bearer ".$accessToken
            ])->get($api_url);


            $status = $response->status();

            $responseData = $response->body();

            $csvData = $responseData;



            if($status == 200){
                $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
                // $storageFile = Storage::append('CustomerPortalSummary.csv', $csvData);
                return array("status" => 1, "response" => $data);
                /*
                $response = $response->body();
                return $response;*/

            }else{
                return array("status" => 0);
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
    }
}
