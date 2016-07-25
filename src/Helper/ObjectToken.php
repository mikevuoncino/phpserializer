<?php

namespace MVuoncino\Helper;

class ObjectToken extends AbstractToken
{
    private $objectName;

    private $members;

    public function __construct($objectName, ArrayToken $members)
    {
        $this->objectName = $objectName;
        $this->members = $members;
    }

    public function toArray()
    {
        return [
            'object' => $this->objectName,
            'members' => $this->members->toArray()
        ];
    }

    public function __toString()
    {
        return sprintf(
            "%s:%d:\"%s\":%d:{%s}",
            $this->getType(),
            strlen($this->objectName),
            $this->objectName,
            $this->members->getKeyValueCount(),
            $this->members->getKeyValuePairString()
        );
    }
}

