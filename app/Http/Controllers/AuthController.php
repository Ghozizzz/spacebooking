<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class AuthController extends Controller
{
  public function signin()
  {
    // Initialize the OAuth client
    $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => env('OAUTH_APP_ID'),
      'clientSecret'            => env('OAUTH_APP_PASSWORD'),
      'redirectUri'             => env('OAUTH_REDIRECT_URI'),
      'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
      'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
      'urlResourceOwnerDetails' => '',
      'scopes'                  => env('OAUTH_SCOPES')
    ]);

    $authUrl = $oauthClient->getAuthorizationUrl();

    // Save client state so we can validate in callback
    session(['oauthState' => $oauthClient->getState()]);

    // Redirect to AAD signin page
    return redirect()->away($authUrl);
  }

  public function callback(Request $request)
  {
    // Validate state
    $expectedState = session('oauthState');
    $request->session()->forget('oauthState');
    $providedState = $request->query('state');

    if (!isset($expectedState)) {
      // If there is no expected state in the session,
      // do nothing and redirect to the home page.
      return redirect()->route('login.index');
    }

    if (!isset($providedState) || $expectedState != $providedState) {
      return redirect()->route('login.index')
        ->with('error', 'Invalid auth state')
        ->with('errorDetail', 'The provided auth state did not match the expected value');
    }

    // Authorization code should be in the "code" query param
    $authCode = $request->query('code');
    if (isset($authCode)) {
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => env('OAUTH_SCOPES')
      ]);

      try {
        // Make the token request
        $accessToken = $oauthClient->getAccessToken('authorization_code', [
          'code' => $authCode
        ]);
      
        $graph = new Graph();
        $graph->setAccessToken($accessToken->getToken());
      
        $user = $graph->createRequest('GET', '/me')
          ->setReturnType(Model\User::class)
          ->execute();

        $newUser = User::updateOrCreate(
          ['email' => $user->getMail()],
          ['name' => $user->getDisplayName(), 'phone' => $user->getMobilePhone()]
        );

        $newUser = User::find($newUser->id);

        if($newUser->active == 0){
          return redirect()->route('login.suspended');
        }

        $tokenCache = new TokenCache();
        $tokenCache->storeTokens($accessToken, $user);

        if(!is_null(session('redirect_url'))){
          return redirect(session('redirect_url'));
        }

        return redirect()->route('home.index');
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return redirect()->route('login.index')
          ->with('error', 'Error requesting access token')
          ->with('errorDetail', $e->getMessage());
      }
    }

    return redirect()->route('login.index')
      ->with('error', $request->query('error'))
      ->with('errorDetail', $request->query('error_description'));
  }

  public function callback_test(){
    // $user['email'] = 'test@gmail.com';
    // $user['displayName'] = 'test ajah';

    // $user['email'] = 'faculty@gmail.com';
    // $user['displayName'] = 'faculty ajah';

    $user['email'] = 'test.booking@student.uph.edu';
    $user['displayName'] = 'Test Student';

    //LOGIC BARU NIH
    if(strpos($user['email'], '@student.uph.edu')){
      $user['u_type'] = 1;//Student
    }else{
      $user['u_type'] = 0;//Staff
    }

    $user['phone'] = '084282482848';
    $newUser = User::updateOrCreate(
      ['email' => $user['email']],
      ['name' => $user['displayName'], 'phone' => $user['phone'], 'u_type' => $user['u_type']]
    );

    $newUser = User::find($newUser->id);

    $accessToken = [
      'oauth_token' => '01826419861491',
      'oatuh_token_secret' => '6731de76-14a6-49ae-97bc-6eba6914391e'
    ];
    $tokenCache = new TokenCache();
    $tokenCache->storeTokens($accessToken, $user);
    return redirect()->route('home.index');
  }

  public function signout(){
        $tokenCache = new TokenCache();
        $tokenCache->clearTokens();
        return redirect()->route('login.index');
    }
}