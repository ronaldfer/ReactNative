<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

namespace App\TokenStore;
use App\Models\OneDriveTokenManage;

class TokenCache {
  public function storeTokens($accessToken, $user) {
    session([
      'accessToken'   => $accessToken->getToken(),
      'refreshToken'  => $accessToken->getRefreshToken(),
      'tokenExpires'  => $accessToken->getExpires(),
      'userName'      => $user->getDisplayName(),
      'userEmail'     => null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName()
    ]);
    /*store token in database*/
    $data = OneDriveTokenManage::findOrfail(1)->update([
      'accessToken'   => $accessToken->getToken(),
      'refreshToken'  => $accessToken->getRefreshToken(),
      'user_id'       => $user->getMail(),
    ]);
  }

  public function clearTokens() {
    session()->forget('accessToken');
    session()->forget('refreshToken');
    session()->forget('tokenExpires');
    session()->forget('userName');
    session()->forget('userEmail');
  }

  // <GetAccessTokenSnippet>
  public function getAccessToken() {
    // Check if tokens exist
    if (empty(session('accessToken')) ||  empty(session('refreshToken')) || empty(session('tokenExpires'))) {
      return '';
    }

    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if (session('tokenExpires') <= $now) {
      // Token is expired (or very close to it)
      // so let's refresh

      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider(config('app.one_drive'));

      try {
        $newToken = $oauthClient->getAccessToken('refresh_token', [
          'refresh_token' => session('refreshToken')
        ]);

        // Store the new values
        $this->updateTokens($newToken);

        return $newToken->getToken();
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }

    // Token is still valid, just return it
    return session('accessToken');
  }
  // </GetAccessTokenSnippet>

  // <UpdateTokensSnippet>
  public function updateTokens($accessToken) {
    session([
      'accessToken' => $accessToken->getToken(),
      'refreshToken' => $accessToken->getRefreshToken(),
      'tokenExpires' => $accessToken->getExpires()
    ]);

    $data = OneDriveTokenManage::findOrfail(1)->update([
      'accessToken' => $accessToken->getToken(),
      'refreshToken' => $accessToken->getRefreshToken(),
    ]);   
  }
  // </UpdateTokensSnippet>
}
