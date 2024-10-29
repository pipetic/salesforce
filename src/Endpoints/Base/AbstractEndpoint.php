<?php

namespace Pipetic\Salesforce\Endpoints\Base;

use Pipetic\Salesforce\Abstract\Behaviours\HasAccessToken;
use Pipetic\Salesforce\Abstract\Behaviours\HasOauthConfig;

abstract class AbstractEndpoint extends \ByTIC\RestClient\Endpoints\AbstractEndpoint
{
    use HasAccessToken;
    use HasOauthConfig;
}