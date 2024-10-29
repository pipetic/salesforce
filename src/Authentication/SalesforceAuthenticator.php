<?php

namespace Pipetic\Salesforce\Authentication;

use Exception;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Pipetic\Salesforce\Authentication\Token\TokenRepositoryInterface;
use Pipetic\Salesforce\Config\OauthConfig;
use Stevenmaguire\OAuth2\Client\Provider\Salesforce;
use Stevenmaguire\OAuth2\Client\Token\AccessToken;

class SalesforceAuthenticator
{
    protected $oauth2Provider = null;

    protected TokenRepositoryInterface $tokenRepository;

    public function __construct(array|OauthConfig $options, $tokenRepository, $oauth2Provider = null)
    {
        $this->oauth2Provider = $oauth2Provider ?? $this->initOauth2Provider($options);
        $this->tokenRepository = $tokenRepository;
    }

    public function isAuthorized(): bool
    {
        $token = $this->tokenRepository->get();
        if (!$token || !($token instanceof AccessToken)) {
            return false;
        }

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

    protected function initOauth2Provider(array $options = [])
    {
        $authConfig = OauthConfig::from($options);

        $this->oauth2Provider = new Salesforce([
            'clientId' => $authConfig->getClientId(),
            'clientSecret' => $authConfig->getClientSecret(),
            'redirectUri' => $authConfig->getRedirectUri(),
            // optional, defaults to https://login.salesforce.com
//            'domain' => '{custom-salesforce-domain}'
        ]);
    }
}