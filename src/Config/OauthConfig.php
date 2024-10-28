<?php

namespace Pipetic\Salesforce\Config;
class OauthConfig
{
    public const CLIENT_ID_KEY = 'client_id';
    public const CLIENT_SECRET_KEY = 'client_secret';

    public const REDIRECT_URI_KEY = 'redirect_uri';

    protected $clientId = null;

    protected $clientSecret = null;

    protected $redirectUri = null;

    public static function fromConfig($config = null)
    {
        if ($config) {
            return static::from($config);
        }
        $config = config('pipetic.salesforce.oauth');
        if ($config) {
            return static::from($config);
        }
        $config = config('services.salesforce.oauth');
        if ($config) {
            return static::from($config);
        }

        throw new \Exception('Invalid config');
    }

    public static function from($data)
    {
        if ($data instanceof OauthConfig) {
            return $data;
        }
        if ($data instanceof \ArrayAccess) {
            return static::fromArray($data->toArray());
        }
        if (is_array($data)) {
            return static::fromArray($data);
        }
        throw new \Exception('Invalid data');
    }

    public static function fromArray(array $options = [])
    {
        $config = new static();
        $config->setClientId($options[self::CLIENT_ID_KEY]);
        $config->setClientSecret($options[self::CLIENT_SECRET_KEY]);
        $config->setRedirectUri($options[self::REDIRECT_URI_KEY]);
        return $config;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}