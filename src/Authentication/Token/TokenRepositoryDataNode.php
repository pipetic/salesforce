<?php

namespace Pipetic\Salesforce\Authentication\Token;

use ByTIC\DataObjects\Casts\Metadata\Metadata;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Pipetic\Bundle\DataNodes\Models\DataNode;
use Stevenmaguire\OAuth2\Client\Token\AccessToken;

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
        $metadata = $this->node->metadata;
        $metadata->set(self::METADATA_KEY, $token);
        $this->node->save();
    }

    public function get(): ?AccessTokenInterface
    {
        /** @var Metadata $metadata */
        $metadata = $this->node->metadata;
        $accessToken = $metadata->get(self::METADATA_KEY);
        if (!$accessToken) {
            return null;
        }
        $accessToken = new AccessToken($accessToken);
        return $accessToken;
    }
}
