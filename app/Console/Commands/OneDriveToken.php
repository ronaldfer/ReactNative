<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use App\Models\OneDriveTokenManage;
use App\Models\Project;
use App\Models\ProjectRelease;
use Http;

class OneDriveToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onedrivetoken:token';

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
        \Log::info(date('Y-m-d H:i:s').' Token generate kiya');
        $getRefreshToken = OneDriveTokenManage::find(1)->get(['refreshToken']);

        $refreshToken = $getRefreshToken[0]['refreshToken'];
        // OneDriveTokenManage::
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
}
