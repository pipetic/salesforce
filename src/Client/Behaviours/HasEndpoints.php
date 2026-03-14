<?php

namespace Pipetic\Salesforce\Client\Behaviours;

use Pipetic\Salesforce\Endpoints\Base\AbstractEndpoint;
use Pipetic\Salesforce\Endpoints\Oauth2\Introspect\IntrospectEndpoint;
use Pipetic\Salesforce\Endpoints\SObjects\SObjectsEndpoint;
use Pipetic\Salesforce\Endpoints\SObjects\SObjectsQueryEndpoint;

trait HasEndpoints
{
    public function oauth2Introspect(): IntrospectEndpoint
    {
        return $this->getEndpointWithToken(IntrospectEndpoint::class);
    }

    public function sobjects(): SObjectsEndpoint
    {
        return $this->getEndpointWithToken(SObjectsEndpoint::class);
    }

    public function query(): SObjectsQueryEndpoint
    {
        return $this->getEndpointWithToken(SObjectsQueryEndpoint::class);
    }

    protected function getEndpointWithToken(string $class): AbstractEndpoint
    {
        /** @var AbstractEndpoint $endpoint */
        $endpoint = $this->getEndpoint($class);
        $endpoint->setAccessToken($this->getAccessToken());
        $endpoint->setAuthenticatorOptions($this->getAuthenticatorOptions());
        return $endpoint;
    }
}
