<?php

namespace Pipetic\Salesforce\Client\Behaviours;

use Pipetic\Salesforce\Endpoints\Base\AbstractEndpoint;
use Pipetic\Salesforce\Endpoints\Oauth2\Introspect\IntrospectEndpoint;

trait HasEndpoints
{
    public function oauth2Introspect(): IntrospectEndpoint
    {
        return $this->getEndpointWithToken(IntrospectEndpoint::class);
    }

    protected function getEndpointWithToken($class): AbstractEndpoint
    {
        /** @var AbstractEndpoint $endpoint */
        $endpoint = $this->getEndpoint($class);
        $endpoint->setAccessToken($this->getAccessToken());
        $endpoint->setAuthenticatorOptions($this->getAuthenticatorOptions());
        return $endpoint;
    }
}
