<?php

namespace Pipetic\Salesforce\Abstract\Behaviours;

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