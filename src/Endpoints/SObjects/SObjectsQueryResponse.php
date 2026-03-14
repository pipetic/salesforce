<?php

namespace Pipetic\Salesforce\Endpoints\SObjects;

class SObjectsQueryResponse
{
    protected int $totalSize = 0;

    protected bool $done = false;

    protected array $records = [];

    protected ?string $nextRecordsUrl = null;

    public static function fromArray(array $data): static
    {
        $response = new static();
        $response->populate($data);
        return $response;
    }

    public function populate(array $data): void
    {
        $this->totalSize = (int) ($data['totalSize'] ?? 0);
        $this->done = (bool) ($data['done'] ?? false);
        $this->records = $data['records'] ?? [];
        $this->nextRecordsUrl = $data['nextRecordsUrl'] ?? null;
    }

    public function getTotalSize(): int
    {
        return $this->totalSize;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    public function getNextRecordsUrl(): ?string
    {
        return $this->nextRecordsUrl;
    }
}
