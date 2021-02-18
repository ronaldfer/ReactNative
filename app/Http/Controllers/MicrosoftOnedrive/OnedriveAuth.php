<?php
namespace App\Http\Controllers\MicrosoftOnedrive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\Models\OneDriveTokenManage;

class OnedriveAuth extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //

    }

    /*microsoft api code*/

    public function signin(){
        // Initialize the OAuth client
        /*dd([
            "client_id"      =>config('onedrive.one_drive.clientId'),
            "client_secret"  =>config('onedrive.one_drive.clientSecret'),
            "redirect_uri"   =>config('onedrive.one_drive.redirectUri'),
            'urlAuthorize'   =>config('onedrive.one_drive.urlAuthorize'),
            'urlAccessToken' => config('onedrive.one_drive.urlAccessToken'),
            'urlResourceOwnerDetails' => config('onedrive.one_drive.urlResourceOwnerDetails'),
            "scope"          =>config('onedrive.one_drive.scopes'),
        ]);*/
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            "clientId"      =>config('onedrive.one_drive.clientId'),
            "clientSecret"  =>config('onedrive.one_drive.clientSecret'),
            "redirectUri"   =>config('onedrive.one_drive.redirectUri'),
            'urlAuthorize'   =>config('onedrive.one_drive.urlAuthorize'),
            'urlAccessToken' => config('onedrive.one_drive.urlAccessToken'),
            'urlResourceOwnerDetails' => config('onedrive.one_drive.urlResourceOwnerDetails'),
            "scopes"          =>config('onedrive.one_drive.scopes'),
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();
        // dd($authUrl);
        // Save client state so we can validate in callback
        session(['oauthState' => $oauthClient->getState() ]);

        // Redirect to AAD signin page
        return redirect()->away($authUrl);
    }

    public function callback(Request $request){
        // Validate state
        $expectedState = session('oauthState');
        $request->session()->forget('oauthState');
        $providedState = $request->query('state');

        if (!isset($expectedState)){
            // If there is no expected state in the session,
            // do nothing and redirect to the home page.
            return redirect('/signin');
            // return redirect('/');
        }

        if (!isset($providedState) || $expectedState != $providedState){
            return redirect('/')->with('error', 'Invalid auth state')
                ->with('errorDetail', 'The provided auth state did not match the expected value');
        }

        // Authorization code should be in the "code" query param
        $authCode = $request->query('code');
        if (isset($authCode)){
            // Initialize the OAuth client
            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                "clientId"                  => config('onedrive.one_drive.clientId'),
                "clientSecret"              => config('onedrive.one_drive.clientSecret'),
                "redirectUri"               => config('onedrive.one_drive.redirectUri'),
                'urlAuthorize'              => config('onedrive.one_drive.urlAuthorize'),
                'urlAccessToken'            => config('onedrive.one_drive.urlAccessToken'),
                'urlResourceOwnerDetails'   => config('onedrive.one_drive.urlResourceOwnerDetails'),
                "scopes"                    => config('onedrive.one_drive.scopes'),
            ]);
            // <StoreTokensSnippet>
            try{
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                  'code' => $authCode
                ]);

                /*store token in db*/
                $update_data = OneDriveTokenManage::findOrfail(1)->update([
                  'accessToken' => $accessToken->getToken(),
                  'refreshToken'=>$accessToken->getRefreshToken()
                ]);
                dd($update_data);
                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());

                $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();
                  $request->session()->flash('status', 'Onedrive account successfully connected.');
                  echo "<pre>"; print_r($accessToken); echo "</pre>";
                  dd($user);
                  // return redirect('/');
            }
            // </StoreTokensSnippet>
            catch(League\OAuth2\Client\Provider\Exception\IdentityProviderException $e)
            {
                return redirect('/')->with('error', 'Error requesting access token')
                    ->with('errorDetail', $e->getMessage());
            }
        }
        return redirect('/')
            ->with('error', $request->query('error'))
            ->with('errorDetail', $request->query('error_description'));
    }

    // <SignOutSnippet>
    public function signout(){
        $tokenCache = new TokenCache();
        $tokenCache->clearTokens();
        return redirect('/');
    }
    // </SignOutSnippet>
    public function releaseFolder($id){
        /*$viewData = $this->loadViewData();*/

        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $queryParams = array(
          '$select' => 'subject,organizer,start,end',
          '$orderby' => 'createdDateTime DESC'
        );

        // Append query parameters to the '/me/events' url

        $getEventsUrl = '/me/drive/items/'.$id.'/children';

        $events = $graph->createRequest('GET', $getEventsUrl)
          ->setReturnType(Model\DriveItem::class)
          ->execute();
          // dd($events);
        $viewData['events'] = $events;
        $data = $viewData['events'];
        echo "<pre> <b>releaseFolder</b>";
        dd($data);
        echo "</pre>";
    /*    return view('calendar',compact('data'));*/
    }
}

