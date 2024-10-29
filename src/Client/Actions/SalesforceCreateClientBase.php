<?php

namespace Pipetic\Salesforce\Client\Actions;

use Bytic\Actions\Action;
use Pipetic\Salesforce\Client\SalesforceClient;
use Pipetic\Salesforce\Config\ClientConfiguration;
use Pipetic\Salesforce\Config\OauthConfig;

class SalesforceCreateClientBase extends  Action
{
    protected $oauthConfig = null;

    public function handle()
    {
        $client = $this->generateClient();
        return $client;
    }

    protected function generateClient(): SalesforceClient
    {
        $configuration = $this->generateClientConfiguration();
        $client = new SalesforceClient(null, $configuration);
        return $client;
    }

    protected function generateClientConfiguration(): ClientConfiguration
    {
        $configuration = new ClientConfiguration();
        $configuration->setAuthenticatorOptions($this->getOauthConfig());
        return $configuration;
    }

    public function getOauthConfig()
    {
        if ($this->oauthConfig === null) {
            $this->oauthConfig = $this->generateOauthConfig();
        }
        return $this->oauthConfig;
    }

    protected function generateOauthConfig(): OauthConfig
    {
        return OauthConfig::fromConfig();
    }
}

