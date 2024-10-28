<?php

namespace Pipetic\Salesforce\Client;

class SalesforceClient
{
    use Behaviours\HasAuthenticatorTrait;
    use Behaviours\HasDataNode;

    public function __construct()
    {
    }


    public static function create()
    {
        return new static();
    }
}