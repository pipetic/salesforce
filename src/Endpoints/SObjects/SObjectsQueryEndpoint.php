<?php

namespace Pipetic\Salesforce\Endpoints\SObjects;

use ByTIC\RestClient\Endpoints\Traits\DynamicMethod;
use ByTIC\RestClient\Endpoints\Traits\HasClient;
use ByTIC\RestClient\Utility\Traits\HasUri;
use Pipetic\Salesforce\Client\SalesforceClient;
use Pipetic\Salesforce\Endpoints\Base\AbstractEndpoint;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Endpoint for executing SOQL queries against Salesforce.
 */
class SObjectsQueryEndpoint extends AbstractEndpoint
{
    use HasUri;
    use HasClient;
    use DynamicMethod;

    protected function getBaseUri(): string
    {
        return sprintf(
            '%s/%s%s',
            SalesforceClient::DATA_ENDPOINT,
            SalesforceClient::API_VERSION,
            SalesforceClient::QUERY_ENDPOINT
        );
    }

    /**
     * Execute a SOQL query.
     *
     * @param string $soql The SOQL query string.
     * @return SObjectsQueryResponse
     */
    public function query(string $soql): SObjectsQueryResponse
    {
        $this->setUri($this->getBaseUri() . '?' . http_build_query(['q' => $soql]));
        $this->setMethod('GET');

        return $this->execute();
    }

    /**
     * Fetch the next page of results from a query using the nextRecordsUrl.
     *
     * @param string $nextRecordsUrl The URL returned in a paginated query response.
     * @return SObjectsQueryResponse
     */
    public function nextPage(string $nextRecordsUrl): SObjectsQueryResponse
    {
        $this->setUri($nextRecordsUrl);
        $this->setMethod('GET');

        return $this->execute();
    }

    protected function transformResponseBody(string $body, int $status, SerializerInterface $serializer, string $contentType = null): SObjectsQueryResponse
    {
        $data = json_decode($body, true);
        return SObjectsQueryResponse::fromArray($data);
    }
}
