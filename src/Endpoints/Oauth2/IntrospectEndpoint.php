<?php

namespace Pipetic\Salesforce\Endpoints\Oauth2;

use ByTIC\RestClient\Endpoints\Traits\DynamicMethod;
use ByTIC\RestClient\Endpoints\Traits\HasClient;
use ByTIC\RestClient\Utility\Traits\HasUri;
use Pipetic\Salesforce\Endpoints\Base\AbstractEndpoint;
use Symfony\Component\Serializer\SerializerInterface;

class IntrospectEndpoint extends AbstractEndpoint
{
    /**
     * @var string
     */
    const BASE_URI = '/services/oauth2/introspect';

    use HasUri;
    use HasClient;
    use DynamicMethod;

    protected function transformResponseBody(string $body, int $status, SerializerInterface $serializer, string $contentType = null)
    {
        // TODO: Implement transformResponseBody() method.
    }
}

