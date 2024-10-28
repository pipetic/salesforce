<?php

namespace Pipetic\Salesforce\Client\Behaviours;

use Pipetic\Salesforce\Authentication\SalesforceAuthenticator;
use Pipetic\Salesforce\Authentication\Token\TokenRepositoryDataNode;

trait HasAuthenticatorTrait
{
    protected $authenticator = null;

    protected array $authenticatorOptions = [];

    public function __construct()
    {
    }

    public function isAuthorized()
    {
        return false;
    }

    public function getAuthenticator()
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

    protected function generateAuthenticator()
    {
        $tokenRepository = new TokenRepositoryDataNode($this->getDataNode());
        $options = $this->getAuthenticatorOptions();
        return new SalesforceAuthenticator($options, $tokenRepository);
    }
}