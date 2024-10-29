<?php

namespace Pipetic\Salesforce\Client\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Salesforce\Client\SalesforceClient;
use Pipetic\Salesforce\Config\OauthConfig;

class SalesforceCreateClientBase extends  Action
{
    protected $oauthConfig = null;

    public function handle()
    {
        $client = $this->generateClient();
        $this->initAuthenticator($client);
        return $client;
    }

    public function setOauthConfig($oauthConfig)
    {
        $this->oauthConfig = $oauthConfig;
        return $this;
    }

    public function getOauthConfig()
    {
        if ($this->oauthConfig === null) {
            $this->oauthConfig = $this->generateOauthConfig();
        }
        return $this->oauthConfig;
    }

    protected function generateOauthConfig()
    {
        return OauthConfig::fromConfig();
    }

    protected function initAuthenticator(SalesforceClient $client)
    {
        $client->setAuthenticatorOptions($this->getOauthConfig());
    }

    protected function generateClient(): SalesforceClient
    {
        $client = new SalesforceClient();
        return $client;
    }
}

