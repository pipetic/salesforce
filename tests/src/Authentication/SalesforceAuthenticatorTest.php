<?php

namespace Pipetic\Salesforce\Tests\Authentication;

use Exception;
use League\OAuth2\Client\Token\AccessTokenInterface;
use PHPUnit\Framework\TestCase;
use Pipetic\Salesforce\Authentication\SalesforceAuthenticator;
use Pipetic\Salesforce\Authentication\Token\TokenRepositoryInterface;
use Pipetic\Salesforce\Config\OauthConfig;
use Stevenmaguire\OAuth2\Client\Token\AccessToken;

class SalesforceAuthenticatorTest extends TestCase
{
    private TokenRepositoryInterface $tokenRepository;
    private $oauth2Provider;
    private SalesforceAuthenticator $authenticator;

    protected function setUp(): void
    {
        $this->tokenRepository = $this->createMock(TokenRepositoryInterface::class);
        $this->oauth2Provider = $this->getMockBuilder(\Stevenmaguire\OAuth2\Client\Provider\Salesforce::class)
            ->disableOriginalConstructor()
            ->getMock();

        $config = new OauthConfig();
        $config->setClientId('test_client_id');
        $config->setClientSecret('test_client_secret');
        $config->setRedirectUri('https://example.com/callback');

        $this->authenticator = new SalesforceAuthenticator($config, $this->tokenRepository, $this->oauth2Provider);
    }

    public function testIsAuthorizedReturnsFalseWhenNoToken(): void
    {
        $this->tokenRepository->method('get')->willReturn(null);

        $this->assertFalse($this->authenticator->isAuthorized());
    }

    public function testIsAuthorizedReturnsTrueWhenTokenIsValid(): void
    {
        $token = new AccessToken([
            'access_token' => 'valid_token',
            'expires_in' => 3600,
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $this->tokenRepository->method('get')->willReturn($token);

        $this->assertTrue($this->authenticator->isAuthorized());
    }

    public function testIsAuthorizedReturnsFalseWhenTokenIsExpired(): void
    {
        $token = new AccessToken([
            'access_token' => 'expired_token',
            'expires' => time() - 3600,
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $this->tokenRepository->method('get')->willReturn($token);

        $this->assertFalse($this->authenticator->isAuthorized());
    }

    public function testAuthenticateReturnsExistingValidToken(): void
    {
        $token = new AccessToken([
            'access_token' => 'existing_token',
            'expires_in' => 3600,
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $this->tokenRepository->method('get')->willReturn($token);

        $result = $this->authenticator->authenticate([]);

        $this->assertSame($token, $result);
    }

    public function testAuthenticateThrowsExceptionWhenCodeIsMissing(): void
    {
        $this->tokenRepository->method('get')->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing code in request');

        $this->authenticator->authenticate([]);
    }

    public function testAuthenticateExchangesCodeForToken(): void
    {
        $newToken = new AccessToken([
            'access_token' => 'new_token',
            'expires_in' => 3600,
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $this->tokenRepository->method('get')->willReturn(null);
        $this->tokenRepository->expects($this->once())->method('save')->with($this->isInstanceOf(AccessToken::class));
        $this->oauth2Provider->method('getAccessToken')->willReturn($newToken);

        $result = $this->authenticator->authenticate(['code' => 'auth_code_123']);

        $this->assertInstanceOf(AccessTokenInterface::class, $result);
    }

    public function testRefreshThrowsExceptionWhenNoToken(): void
    {
        $this->tokenRepository->method('get')->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No existing access token to refresh');

        $this->authenticator->refresh();
    }

    public function testRefreshReturnsExistingTokenWhenNotExpired(): void
    {
        $token = new AccessToken([
            'access_token' => 'valid_token',
            'expires_in' => 3600,
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $this->tokenRepository->method('get')->willReturn($token);
        $this->tokenRepository->expects($this->never())->method('save');

        $result = $this->authenticator->refresh();

        $this->assertSame($token, $result);
    }

    public function testRefreshSavesNewTokenWhenExpired(): void
    {
        $expiredToken = new AccessToken([
            'access_token' => 'expired_token',
            'expires' => time() - 3600,
            'refresh_token' => 'refresh_token_xyz',
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $newToken = new AccessToken([
            'access_token' => 'refreshed_token',
            'expires_in' => 3600,
            'instance_url' => 'https://myorg.salesforce.com',
        ]);

        $this->tokenRepository->method('get')->willReturn($expiredToken);
        $this->tokenRepository->expects($this->once())->method('save')->with($newToken);
        $this->oauth2Provider->method('getAccessToken')->with('refresh_token', ['refresh_token' => 'refresh_token_xyz'])->willReturn($newToken);

        $result = $this->authenticator->refresh();

        $this->assertSame($newToken, $result);
    }

    public function testGetAuthorizationUrlReturnsUrl(): void
    {
        $this->oauth2Provider->method('getAuthorizationUrl')->willReturn('https://login.salesforce.com/services/oauth2/authorize?...');

        $url = $this->authenticator->getAuthorizationUrl();

        $this->assertStringContainsString('salesforce.com', $url);
    }
}
