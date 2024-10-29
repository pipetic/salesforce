<?php

namespace Pipetic\Salesforce\Client\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;

class SalesforceCreateClientDataNode extends SalesforceCreateClientBase
{
    use HasSubject;

    public function handle()
    {
        $client = parent::handle();
        $client->setDataNode($this->getSubject());

        return $client;
    }

}

