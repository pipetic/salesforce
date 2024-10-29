<?php

namespace Pipetic\Salesforce\Client\Behaviours;

use League\OAuth2\Client\Token\AccessTokenInterface;
use Pipetic\Salesforce\Abstract\Behaviours\HasAccessToken;
use Pipetic\Salesforce\Abstract\Behaviours\HasOauthConfig;
use Pipetic\Salesforce\Authentication\SalesforceAuthenticator;
use Pipetic\Salesforce\Authentication\Token\TokenRepositoryDataNode;
use Pipetic\Salesforce\Config\OauthConfig;
use Stevenmaguire\OAuth2\Client\Token\AccessToken;

trait HasAuthenticatorTrait
{
    use HasAccessToken;
    use HasOauthConfig;

    protected SalesforceAuthenticator|null $authenticator = null;

    protected function discoverAccessToken()
    {
        return $this->getAuthenticator()->getAccessToken();
    }

    /**
     * Get the current instance url.
     * @return string $instanceUrl - The current instance url.
     */
    public function getInstanceUrl(): string
    {
        return $this->getAccessToken()?->getInstanceUrl() ?? '';
    }

    public function isAuthorized(): bool
    {
        return $this->getAuthenticator()->isAuthorized();
    }

    public function authenticate($request = null): ?AccessTokenInterface
    {
        return $this->getAuthenticator()->authenticate($request);
    }

    public function getAuthenticator(): ?SalesforceAuthenticator
    {
        if ($this->authenticator === null) {
            $this->initAuthenticator();
        }
        return $this->authenticator;
    }

    protected function initAuthenticator()
    {
        $this->authenticator = $this->generateAuthenticator();
    }

    protected function generateAuthenticator(): SalesforceAuthenticator
    {
        $tokenRepository = new TokenRepositoryDataNode($this->getDataNode());
        $options = $this->getAuthenticatorOptions();
        return new SalesforceAuthenticator($options, $tokenRepository);
    }
}