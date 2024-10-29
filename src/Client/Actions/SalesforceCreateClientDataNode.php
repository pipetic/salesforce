<?php

namespace Pipetic\Salesforce\Client\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Salesforce\Config\ClientConfiguration;

class SalesforceCreateClientDataNode extends SalesforceCreateClientBase
{
    use HasSubject;

    protected function generateClientConfiguration(): ClientConfiguration
    {
        $configuration = parent::generateClientConfiguration();
        $configuration->setDataNode($this->getSubject());

        return $configuration;
    }

}

