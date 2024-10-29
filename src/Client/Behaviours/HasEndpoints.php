<?php

namespace Pipetic\Salesforce\Client\Behaviours;

use Pipetic\Salesforce\Endpoints\Base\AbstractEndpoint;
use Pipetic\Salesforce\Endpoints\Oauth2\IntrospectEndpoint;

trait HasEndpoints
{
    public function oauth2Introspect(): \ByTIC\RestClient\Endpoints\AbstractEndpoint
    {
        return $this->getEndpointWithToken(IntrospectEndpoint::class);
    }

    protected function getEndpointWithToken($class): AbstractEndpoint
    {
        /** @var AbstractEndpoint $endpoint */
        $endpoint = $this->getEndpoint($class);
        $endpoint->setAccessToken($this->getAccessToken());
        return $endpoint;
    }
}
