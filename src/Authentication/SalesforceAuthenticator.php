<?php

namespace Pipetic\Salesforce\Authentication;

use Pipetic\Salesforce\Authentication\Token\TokenRepositoryInterface;
use Pipetic\Salesforce\Config\OauthConfig;

class SalesforceAuthenticator
{
    protected $oauth2Provider = null;

    protected TokenRepositoryInterface $tokenRepository;

    public function __construct(array|OauthConfig $options, $tokenRepository, $oauth2Provider = null)
    {
        $this->oauth2Provider = $oauth2Provider ?? $this->initOauth2Provider($options);
        $this->tokenRepository = $tokenRepository;
    }

    public function authenticate()
    {
        $existingAccessToken = $this->tokenRepository->get();
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

        $this->oauth2Provider = new \Stevenmaguire\OAuth2\Client\Provider\Salesforce([
            'clientId' => $authConfig->getClientId(),
            'clientSecret' => $authConfig->getClientSecret(),
            'redirectUri' => $authConfig->getRedirectUri(),
            // optional, defaults to https://login.salesforce.com
//            'domain' => '{custom-salesforce-domain}'
        ]);
    }
}