<?php

namespace Pipetic\Salesforce\Endpoints\Base;

use Pipetic\Salesforce\Abstract\Behaviours\HasAccessToken;

abstract class AbstractEndpoint extends \ByTIC\RestClient\Endpoints\AbstractEndpoint
{
    use HasAccessToken;
}