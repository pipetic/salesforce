<?php

namespace Pipetic\Salesforce\Endpoints\Oauth2\Introspect;

use ByTIC\RestClient\Endpoints\Traits\DynamicMethod;
use ByTIC\RestClient\Endpoints\Traits\HasClient;
use ByTIC\RestClient\Utility\Traits\HasUri;
use League\OAuth2\Client\Token\AccessToken;
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

    public function inspect(AccessToken $accessToken)
    {
        $this->bodyData = [
            "client_id" => $this->getAuthenticatorOptions()->getClientId(),
            "client_secret" => $this->getAuthenticatorOptions()->getClientSecret(),
            "token" => $accessToken->getToken(),
            "token_type_hint" => "access_token"
        ];
        $this->setMethod('POST');

        return $this->execute();
    }

    protected function transformResponseBody(string $body, int $status, SerializerInterface $serializer, string $contentType = null)
    {
        $data = json_decode($body, true);
        return IntrospectResponse::fromArray($data);
    }

    protected function generateBodyHeaders(): array
    {
        $headers = parent::generateBodyHeaders();
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        return $headers;
    }
}

