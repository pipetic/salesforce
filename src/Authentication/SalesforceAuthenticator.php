<?php

namespace Pipetic\Salesforce\Authentication;

use Exception;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Pipetic\Salesforce\Abstract\Behaviours\HasAccessToken;
use Pipetic\Salesforce\Authentication\Token\TokenRepositoryInterface;
use Pipetic\Salesforce\Config\OauthConfig;
use Stevenmaguire\OAuth2\Client\Provider\Salesforce;
use Stevenmaguire\OAuth2\Client\Token\AccessToken;

class SalesforceAuthenticator
{
    use HasAccessToken;

    protected $oauth2Provider = null;

    protected TokenRepositoryInterface $tokenRepository;

    public function __construct(array|OauthConfig $options, $tokenRepository, $oauth2Provider = null)
    {
        $this->oauth2Provider = $oauth2Provider ?? $this->generateOauth2Provider($options);
        $this->tokenRepository = $tokenRepository;
    }

    protected function discoverAccessToken(): ?AccessToken
    {
        $token = $this->tokenRepository->get();
        if (!$token || !($token instanceof AccessToken)) {
            return null;
        }
        return $token;
    }

    public function isAuthorized(): bool
    {
        $token = $this->getAccessToken();
        return !$token->hasExpired();
    }

    public function authenticate($request = null): AccessTokenInterface
    {
        $existingAccessToken = $this->tokenRepository->get();
        if ($existingAccessToken && !$existingAccessToken->hasExpired()) {
            return $existingAccessToken;
        }

        // Optional, only required when PKCE is enabled.
        // Restore the PKCE code stored in the session.
//        $provider->setPkceCode($_SESSION['oauth2pkceCode']);

        $code = $request['code'] ?? null;
        if (!$code) {
            throw new Exception('Missing code in request');
        }
        // Try to get an access token using the authorization code grant.
        $accessToken = $this->oauth2Provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        $values = $accessToken->jsonSerialize();
        $values['expires_in'] = 3 * 31 * 24 * 60 * 60;
        $accessToken = new AccessToken($values);
//        $introspect = $this->introspect($token['access_token']);
//        $token['expires'] = $introspect['exp'];
        $this->tokenRepository->save($accessToken);

        return $accessToken;
    }

    public function refresh()
    {
        $existingAccessToken = $this->tokenRepository->get();

        if ($existingAccessToken->hasExpired()) {
            $newAccessToken = $this->oauth2Provider->getAccessToken('refresh_token', [
                'refresh_token' => $existingAccessToken->getRefreshToken()
            ]);

            // Purge old access token and store new access token to your data store.
        }
    }

//         Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    public function getAuthorizationUrl()
    {
        // (e.g. state).
        $authorizationUrl = $this->oauth2Provider->getAuthorizationUrl();
        // Get the state generated for you and store it to the session.
//            $_SESSION['oauth2state'] = $provider->getState();

        // Optional, only required when PKCE is enabled.
        // Get the PKCE code generated for you and store it to the session.
//            $_SESSION['oauth2pkceCode'] = $provider->getPkceCode();

        return $authorizationUrl;
    }

    protected function generateOauth2Provider(array|OauthConfig $options = [])
    {
        $authConfig = OauthConfig::from($options);

        return new Salesforce([
            'clientId' => $authConfig->getClientId(),
            'clientSecret' => $authConfig->getClientSecret(),
            'redirectUri' => $authConfig->getRedirectUri(),
            // optional, defaults to https://login.salesforce.com
//            'domain' => '{custom-salesforce-domain}'
        ]);
    }
}