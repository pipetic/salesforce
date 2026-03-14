<?php

namespace Pipetic\Salesforce\Tests\Endpoints\Oauth2\Introspect;

use PHPUnit\Framework\TestCase;
use Pipetic\Salesforce\Endpoints\Oauth2\Introspect\IntrospectResponse;

class IntrospectResponseTest extends TestCase
{
    private array $fixtureData;

    protected function setUp(): void
    {
        $this->fixtureData = json_decode(
            file_get_contents(__DIR__ . '/../../../fixtures/requests/oauth/introspect/base_response.json'),
            true
        );
    }

    public function testFromArrayPopulatesAllFields(): void
    {
        $response = IntrospectResponse::fromArray($this->fixtureData);

        $this->assertTrue($response->getActive());
        $this->assertStringContainsString('api', $response->getScope());
        $this->assertSame('xxxxxxxxxxxxxxx', $response->getClientId());
        $this->assertSame('gabriel.solomon@onekind.ro', $response->getUsername());
        $this->assertSame('access_token', $response->getTokenType());
        $this->assertSame(1731520276, $response->getExp());
        $this->assertSame(1731513076, $response->getIat());
        $this->assertSame(1731513076, $response->getNbf());
        $this->assertSame('https://login.salesforce.com/id/00D/005', $response->getSub());
    }

    public function testFromArrayHandlesMissingFields(): void
    {
        $response = IntrospectResponse::fromArray([]);

        $this->assertNull($response->getActive());
        $this->assertNull($response->getScope());
        $this->assertNull($response->getClientId());
        $this->assertNull($response->getUsername());
        $this->assertNull($response->getTokenType());
        $this->assertNull($response->getExp());
        $this->assertNull($response->getIat());
        $this->assertNull($response->getNbf());
        $this->assertNull($response->getSub());
    }

    public function testFromWithArrayCallsFromArray(): void
    {
        $response = IntrospectResponse::from($this->fixtureData);

        $this->assertInstanceOf(IntrospectResponse::class, $response);
        $this->assertTrue($response->getActive());
    }

    public function testFromWithInvalidTypeThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        IntrospectResponse::from('invalid string');
    }

    public function testGetExpReturnsInt(): void
    {
        $response = IntrospectResponse::fromArray(['exp' => 1731520276]);

        $this->assertIsInt($response->getExp());
        $this->assertSame(1731520276, $response->getExp());
    }

    public function testExpIsCastToInt(): void
    {
        $response = IntrospectResponse::fromArray(['exp' => '1731520276']);

        $this->assertIsInt($response->getExp());
        $this->assertSame(1731520276, $response->getExp());
    }
}
