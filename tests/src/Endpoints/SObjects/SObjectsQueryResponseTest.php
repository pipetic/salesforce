<?php

namespace Pipetic\Salesforce\Tests\Endpoints\SObjects;

use PHPUnit\Framework\TestCase;
use Pipetic\Salesforce\Endpoints\SObjects\SObjectsQueryResponse;

class SObjectsQueryResponseTest extends TestCase
{
    public function testFromArrayPopulatesAllFields(): void
    {
        $data = [
            'totalSize' => 5,
            'done' => true,
            'records' => [
                ['Id' => '001XX000003GYn1', 'Name' => 'Edge Communications'],
                ['Id' => '001XX000003GYn2', 'Name' => 'Burlington Textiles Corp of America'],
            ],
            'nextRecordsUrl' => null,
        ];

        $response = SObjectsQueryResponse::fromArray($data);

        $this->assertSame(5, $response->getTotalSize());
        $this->assertTrue($response->isDone());
        $this->assertCount(2, $response->getRecords());
        $this->assertNull($response->getNextRecordsUrl());
    }

    public function testFromArrayHandlesMissingFields(): void
    {
        $response = SObjectsQueryResponse::fromArray([]);

        $this->assertSame(0, $response->getTotalSize());
        $this->assertFalse($response->isDone());
        $this->assertSame([], $response->getRecords());
        $this->assertNull($response->getNextRecordsUrl());
    }

    public function testNextRecordsUrlIsSetWhenPaginated(): void
    {
        $data = [
            'totalSize' => 2000,
            'done' => false,
            'records' => [],
            'nextRecordsUrl' => '/services/data/v52.0/query/01gXXX-2000',
        ];

        $response = SObjectsQueryResponse::fromArray($data);

        $this->assertFalse($response->isDone());
        $this->assertSame('/services/data/v52.0/query/01gXXX-2000', $response->getNextRecordsUrl());
    }

    public function testRecordsAreReturned(): void
    {
        $records = [
            ['Id' => '001', 'Name' => 'Test Account'],
        ];
        $response = SObjectsQueryResponse::fromArray(['records' => $records, 'done' => true, 'totalSize' => 1]);

        $this->assertSame($records, $response->getRecords());
    }
}
