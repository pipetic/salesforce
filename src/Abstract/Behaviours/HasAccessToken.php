<?php

namespace Pipetic\Salesforce\Abstract\Behaviours;

trait HasAccessToken
{
    protected $accessToken = 'dnx';

    public function getAccessToken()
    {
        if ($this->accessToken === 'dnx') {
            $this->accessToken = $this->discoverAccessToken();
        }
        return $this->accessToken;
    }

    public function setAccessToken($accessToken): static
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    protected function discoverAccessToken()
    {
        return null;
    }
}
