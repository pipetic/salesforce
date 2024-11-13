<?php

namespace Pipetic\Salesforce\Client\Behaviours;

trait HasConfiguration
{
    protected $apiVersion;

    protected function constructFromConfiguration($configuration): void
    {
        $this->setAuthenticatorOptions($configuration->getAuthenticatorOptions());
        $this->setDataNode($configuration->getDataNode());
    }

    protected function initConfiguration($configuration = null): void
    {
        $configuration = $configuration ?: $this->discoverConfiguration();
        $this->apiVersion = self::API_VERSION;

        $configuration->setUri($this->buildQueryUrl());

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $configuration->headers()->add($headers);

        parent::initConfiguration($configuration);
    }

    /**
     * @return string
     */
    private function buildQueryUrl(): string
    {
        $baseUri = $this->getInstanceUrl();
        return $baseUri;
        return sprintf(
            '%s%s/%s%s',
            $baseUri,
            self::DATA_ENDPOINT,
            $this->apiVersion,
            self::QUERY_ENDPOINT
        );
    }
}