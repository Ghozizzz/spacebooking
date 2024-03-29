<?php

namespace App\TokenStore;

class TokenCache {
  public function storeTokens($accessToken, $user) {
    session([
      // 'accessToken' => $accessToken->getToken(),
      // 'refreshToken' => $accessToken->getRefreshToken(),
      // 'tokenExpires' => $accessToken->getExpires(),
      // 'userName' => $user->getDisplayName(),
      // 'userEmail' => null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName()

      'accessToken' => '6731de76-14a6-49ae-97bc-6eba6914391e',
      'refreshToken' => '6731de76-14a6-49ae-97bc-6eba6914391e',
      'tokenExpires' => date('Y-m-d h:i:s'),
      'userName' => $user['displayName'],
      'userEmail' => $user['email']
    ]);
  }

  public function clearTokens() {
    session()->forget('accessToken');
    session()->forget('refreshToken');
    session()->forget('tokenExpires');
    session()->forget('userName');
    session()->forget('userEmail');
  }

  public function getAccessToken() {
    // Check if tokens exist
    if (empty(session('accessToken')) ||
        empty(session('refreshToken')) ||
        empty(session('tokenExpires'))) {
      return '';
    }

    return session('accessToken');
  }
}