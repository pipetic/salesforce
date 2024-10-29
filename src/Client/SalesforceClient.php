<?php

namespace Pipetic\Salesforce\Client;

use ByTIC\RestClient\Client\BaseClient;
use Pipetic\Salesforce\Abstract\Behaviours\HasDataNode;
use Pipetic\Salesforce\Config\ClientConfiguration;
use Psr\Http\Client\ClientInterface;

class SalesforceClient extends BaseClient
{
    /**
     * @var string
     */
    const API_VERSION = 'v52.0';

    /**
     * @var string
     */
    const QUERY_ENDPOINT = '/query';

    /**
     * @var string
     */
    const DATA_ENDPOINT = '/services/data';

    /**
     * @var string
     */
    const SOBJECTS_ENDPOINT = '/sobjects';

    use Behaviours\HasAuthenticatorTrait;
    use Behaviours\HasConfiguration;
    use HasDataNode;
    use Behaviours\HasEndpoints;

    public function __construct(ClientInterface $httpClient = null, ClientConfiguration $configuration = null)
    {
        $this->constructFromConfiguration($configuration);
        parent::__construct($httpClient, $configuration);
    }



//    /**
//     * @param string $token
//     * @return array
//     */
//    protected function introspect(string $token): array
//    {
//        $url = $this->url . self::INTROSPECT_ENDPOINT;
//        $body = \http_build_query([
//            "client_id" => $this->clientId,
//            "client_secret" => $this->clientSecret,
//            "token" => $token,
//            "token_type_hint" => "access_token"
//        ]);
//        $options = [
//            "body" => $body,
//            "headers" => [
//                "Accept" => "application/json",
//                "Content-Type" => self::CONTENT_TYPE
//            ]
//        ];
//        $response = $this->httpClient->request(
//            'POST',
//            $url,
//            $options
//        );
//        return $this->getParsedResponse($response);
//    }
}