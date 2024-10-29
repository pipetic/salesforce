<?php

namespace Pipetic\Salesforce\Abstract\Behaviours;

use Pipetic\Salesforce\Config\OauthConfig;

trait HasOauthConfig
{
    protected ?OauthConfig $authenticatorOptions = null;

    public function setAuthenticatorOptions($options): static
    {
        $this->authenticatorOptions = $options;
        return $this;
    }

    public function getAuthenticatorOptions(): ?OauthConfig
    {
        return $this->authenticatorOptions;
    }
}