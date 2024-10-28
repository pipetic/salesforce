<?php

namespace Pipetic\Salesforce\Authentication\Token;

use League\OAuth2\Client\Token\AccessTokenInterface;
use Pipetic\Bundle\DataNodes\Models\DataNode;

class TokenRepositoryDataNode implements TokenRepositoryInterface
{
    protected DataNode $node;

    public function __construct(DataNode $node)
    {
        $this->node = $node;
    }

    public function save(AccessTokenInterface $token)
    {
        // TODO: Implement save() method.
    }

    public function get(): ?AccessTokenInterface
    {
        // TODO: Implement get() method.
    }
}