<?php

namespace Pipetic\Salesforce\Authentication\Token;

use ByTIC\DataObjects\Casts\Metadata\Metadata;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Pipetic\Bundle\DataNodes\Models\DataNode;

class TokenRepositoryDataNode implements TokenRepositoryInterface
{
    public const METADATA_KEY = 'access_token';
    protected DataNode $node;

    public function __construct(DataNode $node)
    {
        $this->node = $node;
    }

    public function save(AccessTokenInterface $token)
    {
        $metadata = $this->node->getMetadata();
        $metadata->set(self::METADATA_KEY, $token);
    }

    public function get(): ?AccessTokenInterface
    {
        /** @var Metadata $metadata */
        $metadata = $this->node->getMetadata();
        $accessToken = $metadata->get(self::METADATA_KEY);
        return $accessToken;
    }
}
