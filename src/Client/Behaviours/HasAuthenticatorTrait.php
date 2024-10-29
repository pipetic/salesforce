<?php

namespace Pipetic\Salesforce\Client\Behaviours;

use Pipetic\Salesforce\Authentication\SalesforceAuthenticator;
use Pipetic\Salesforce\Authentication\Token\TokenRepositoryDataNode;
use Pipetic\Salesforce\Config\OauthConfig;

trait HasAuthenticatorTrait
{
    protected SalesforceAuthenticator|null $authenticator = null;

    protected ?OauthConfig $authenticatorOptions = null;

    public function __construct()
    {
    }

    public function isAuthorized(): bool
    {
        return $this->getAuthenticator()->isAuthorized();
    }

    public function authenticate($request = null): ?\League\OAuth2\Client\Token\AccessTokenInterface
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

    public function setAuthenticatorOptions($options): static
    {
        $this->authenticatorOptions = $options;
        return $this;
    }

    protected function initAuthenticator()
    {
        $this->authenticator = $this->generateAuthenticator();
    }


    protected function getAuthenticatorOptions()
    {
        return $this->authenticatorOptions;
    }

    protected function generateAuthenticator(): SalesforceAuthenticator
    {
        $tokenRepository = new TokenRepositoryDataNode($this->getDataNode());
        $options = $this->getAuthenticatorOptions();
        return new SalesforceAuthenticator($options, $tokenRepository);
    }
}