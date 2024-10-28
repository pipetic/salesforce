<?php

namespace Pipetic\Salesforce\Authentication\Token;

use League\OAuth2\Client\Token\AccessTokenInterface;

interface TokenRepositoryInterface
{

    public function save(AccessTokenInterface $token);

    public function get(): ?AccessTokenInterface;
}