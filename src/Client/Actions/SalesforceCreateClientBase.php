<?php

namespace Pipetic\Salesforce\Client\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Salesforce\Client\SalesforceClient;
use Pipetic\Salesforce\Config\OauthConfig;

class SalesforceCreateClientBase extends  Action
{
    public function handle()
    {
        $client = $this->generateClient();
        $this->initAuthenticator($client);
        return $client;
    }

    protected function initAuthenticator(SalesforceClient $client)
    {
        $config = OauthConfig::fromConfig();
        $client->setAuthenticatorOptions($config);
    }

    protected function generateClient(): SalesforceClient
    {
        $client = new SalesforceClient();
        return $client;
    }
}

