<?php

namespace Pipetic\Salesforce\Endpoints\Oauth2\Introspect;

class IntrospectResponse
{
    protected ?bool $active = null;

    protected ?string $scope = null;

    protected ?string $client_id = null;

    protected ?string $username = null;

    protected ?string $token_type = null;

    /**
     * Integer timestamp, measured in the number of seconds since January 1 1970 UTC,
     * indicating when this token will expire, as defined in JWT.
     */
    protected ?int $exp = null;

    protected ?int $iat = null;

    protected ?int $nbf = null;

    protected ?string $sub = null;

    public static function from(mixed $data): static
    {
        if (is_array($data)) {
            return static::fromArray($data);
        }
        throw new \InvalidArgumentException('Invalid data type: expected array');
    }

    public static function fromArray(array $data): static
    {
        $response = new static();
        $response->populate($data);
        return $response;
    }

    public function populate(array $data): void
    {
        $this->active = $data['active'] ?? null;
        $this->scope = $data['scope'] ?? null;
        $this->client_id = $data['client_id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->token_type = $data['token_type'] ?? null;
        $this->exp = isset($data['exp']) ? (int) $data['exp'] : null;
        $this->iat = isset($data['iat']) ? (int) $data['iat'] : null;
        $this->nbf = isset($data['nbf']) ? (int) $data['nbf'] : null;
        $this->sub = $data['sub'] ?? null;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    public function getExp(): ?int
    {
        return $this->exp;
    }

    public function getIat(): ?int
    {
        return $this->iat;
    }

    public function getNbf(): ?int
    {
        return $this->nbf;
    }

    public function getSub(): ?string
    {
        return $this->sub;
    }
}
