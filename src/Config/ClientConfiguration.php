<?php

namespace Pipetic\Salesforce\Config;
use ByTIC\RestClient\Client\Configuration\Configuration;
use Pipetic\Salesforce\Abstract\Behaviours\HasDataNode;
use Pipetic\Salesforce\Abstract\Behaviours\HasOauthConfig;

class ClientConfiguration extends Configuration
{
    use HasOauthConfig;
    use HasDataNode;
}