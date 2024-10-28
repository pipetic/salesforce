<?php

namespace Pipetic\Salesforce\Client\Behaviours;

trait HasDataNode
{
    protected $dataNode = null;

    public function getDataNode()
    {
        return $this->dataNode;
    }

    public function setDataNode($dataNode): void
    {
        $this->dataNode = $dataNode;
    }
}