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

    public function __construct(array|OauthConfig $options, TokenRepositoryInterface $tokenRepository, $oauth2Provider = null)
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
        if (!$token) {
            return false;
        }
        return $token->hasExpired() == false;
    }

    public function authenticate($request = null): AccessTokenInterface
    {
        $existingAccessToken = $this->tokenRepository->get();
        if ($existingAccessToken && !$existingAccessToken->hasExpired()) {
            return $existingAccessToken;
        }

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
        $this->tokenRepository->save($accessToken);
        $this->setAccessToken($accessToken);

        return $accessToken;
    }

    public function refresh(): AccessTokenInterface
    {
        $existingAccessToken = $this->tokenRepository->get();

        if (!$existingAccessToken) {
            throw new Exception('No existing access token to refresh');
        }

        if ($existingAccessToken->hasExpired()) {
            $newAccessToken = $this->oauth2Provider->getAccessToken('refresh_token', [
                'refresh_token' => $existingAccessToken->getRefreshToken()
            ]);
            $this->tokenRepository->save($newAccessToken);
            $this->setAccessToken($newAccessToken);
            return $newAccessToken;
        }

        return $existingAccessToken;
    }

    public function getAuthorizationUrl(): string
    {
        return $this->oauth2Provider->getAuthorizationUrl();
    }

    protected function generateOauth2Provider(array|OauthConfig $options = []): Salesforce
    {
        $authConfig = OauthConfig::from($options);

        return new Salesforce([
            'clientId' => $authConfig->getClientId(),
            'clientSecret' => $authConfig->getClientSecret(),
            'redirectUri' => $authConfig->getRedirectUri(),
        ]);
    }
}
