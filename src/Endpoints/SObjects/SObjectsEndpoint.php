<?php

namespace Pipetic\Salesforce\Endpoints\SObjects;

use ByTIC\RestClient\Endpoints\Traits\DynamicMethod;
use ByTIC\RestClient\Endpoints\Traits\HasClient;
use ByTIC\RestClient\Utility\Traits\HasUri;
use Pipetic\Salesforce\Client\SalesforceClient;
use Pipetic\Salesforce\Endpoints\Base\AbstractEndpoint;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Endpoint for CRUD operations on Salesforce SObjects.
 */
class SObjectsEndpoint extends AbstractEndpoint
{
    use HasUri;
    use HasClient;
    use DynamicMethod;

    protected function getBaseUri(string $type = ''): string
    {
        $uri = sprintf(
            '%s/%s%s',
            SalesforceClient::DATA_ENDPOINT,
            SalesforceClient::API_VERSION,
            SalesforceClient::SOBJECTS_ENDPOINT
        );

        if ($type !== '') {
            $uri .= '/' . $type;
        }

        return $uri;
    }

    /**
     * Retrieve a record by its SObject type and ID.
     *
     * @param string $type  The SObject type (e.g. "Account").
     * @param string $id    The Salesforce record ID.
     * @param array  $fields Optional list of fields to return.
     * @return array
     */
    public function get(string $type, string $id, array $fields = []): array
    {
        $uri = $this->getBaseUri($type) . '/' . $id;
        if ($fields) {
            $uri .= '?' . http_build_query(['fields' => implode(',', $fields)]);
        }
        $this->setUri($uri);
        $this->setMethod('GET');

        return $this->execute();
    }

    /**
     * Create a new record of the given SObject type.
     *
     * @param string $type The SObject type (e.g. "Account").
     * @param array  $data The field data for the new record.
     * @return array An array with the 'id' of the created record.
     */
    public function create(string $type, array $data): array
    {
        $this->setUri($this->getBaseUri($type));
        $this->setMethod('POST');
        $this->bodyData = $data;

        return $this->execute();
    }

    /**
     * Update an existing record identified by SObject type and ID.
     *
     * @param string $type The SObject type (e.g. "Account").
     * @param string $id   The Salesforce record ID.
     * @param array  $data The fields to update.
     * @return void
     */
    public function update(string $type, string $id, array $data): void
    {
        $this->setUri($this->getBaseUri($type) . '/' . $id);
        $this->setMethod('PATCH');
        $this->bodyData = $data;

        $this->execute();
    }

    /**
     * Delete a record by its SObject type and ID.
     *
     * @param string $type The SObject type (e.g. "Account").
     * @param string $id   The Salesforce record ID.
     * @return void
     */
    public function delete(string $type, string $id): void
    {
        $this->setUri($this->getBaseUri($type) . '/' . $id);
        $this->setMethod('DELETE');

        $this->execute();
    }

    protected function transformResponseBody(string $body, int $status, SerializerInterface $serializer, string $contentType = null): array
    {
        if ($body === '' || $status === 204) {
            return [];
        }
        return json_decode($body, true) ?? [];
    }
}
