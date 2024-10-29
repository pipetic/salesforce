<?php

namespace Pipetic\Salesforce\Client;

class SalesforceClient
{
    use Behaviours\HasAuthenticatorTrait;
    use Behaviours\HasDataNode;

    public function __construct()
    {
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