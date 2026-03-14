<?php

namespace Pipetic\Salesforce\Client;

use ByTIC\RestClient\Client\BaseClient;
use Pipetic\Salesforce\Abstract\Behaviours\HasDataNode;
use Pipetic\Salesforce\Config\ClientConfiguration;
use Psr\Http\Client\ClientInterface;

class SalesforceClient extends BaseClient
{
    const API_VERSION = 'v52.0';

    const QUERY_ENDPOINT = '/query';

    const DATA_ENDPOINT = '/services/data';

    const SOBJECTS_ENDPOINT = '/sobjects';

    use Behaviours\HasAuthenticatorTrait;
    use Behaviours\HasConfiguration;
    use HasDataNode;
    use Behaviours\HasEndpoints;

    public function __construct(?ClientInterface $httpClient = null, ?ClientConfiguration $configuration = null)
    {
        $this->constructFromConfiguration($configuration);
        parent::__construct($httpClient, $configuration);
    }
}
