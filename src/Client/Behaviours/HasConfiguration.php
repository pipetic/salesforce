<?php

namespace Pipetic\Salesforce\Client\Behaviours;

trait HasConfiguration
{
    protected $apiVersion;

    /**
     * @inheritDoc
     */
    protected function discoverConfiguration()
    {
        $this->apiVersion = self::API_VERSION;

        $configuration = parent::discoverConfiguration();
//        $configuration->addFormatSupport('json');

        $configuration->setUri($this->buildQueryUrl());

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $configuration->headers()->add($headers);
        return $configuration;
    }

    /**
     * @return string
     */
    private function buildQueryUrl(): string
    {
        $baseUri = $this->getInstanceUrl();
        return sprintf(
            '%s%s/%s%s',
            $baseUri,
            self::DATA_ENDPOINT,
            $this->apiVersion,
            self::QUERY_ENDPOINT
        );
    }
}