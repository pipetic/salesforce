<?php

namespace Pipetic\Salesforce\Endpoints\Oauth2\Introspect;

class IntrospectResponse
{
    protected $active = null;

    protected $scope = null;

    protected $client_id = null;

    protected $username = null;

    protected $token_type = null;

    /**
     * @var OPTIONAL.  Integer timestamp, measured in the number of seconds
     * since January 1 1970 UTC, indicating when this token will expire,
     * as defined in JWT
     */
    protected $exp = null;

    protected $iat = null;

    protected $nbf = null;

    protected $sub = null;

    public static function from($data)
    {
       if (is_array($data)) {
           return static::fromArray($data);
       }
       throw new \Exception('Invalid data type');
    }

    public static function fromArray($data)
    {
        $response = new static();
        $response->populate($data);
        return $response;
    }

    public function populate($data)
    {
        $this->active = $data['active'] ?? null;
        $this->scope = $data['scope'] ?? null;
        $this->client_id = $data['client_id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->token_type = $data['token_type'] ?? null;
        $this->exp = $data['exp'] ?? null;
        $this->iat = $data['iat'] ?? null;
        $this->nbf = $data['nbf'] ?? null;
        $this->sub = $data['sub'] ?? null;
    }

    /**
     * @return null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return null
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @return null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return null
     */
    public function getTokenType()
    {
        return $this->token_type;
    }

    public function getExp(): ?OPTIONAL
    {
        return $this->exp;
    }

    /**
     * @return null
     */
    public function getIat()
    {
        return $this->iat;
    }

    /**
     * @return null
     */
    public function getNbf()
    {
        return $this->nbf;
    }

    /**
     * @return null
     */
    public function getSub()
    {
        return $this->sub;
    }
}