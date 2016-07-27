<?php

namespace MVuoncino\Helper;

class ObjectToken extends AbstractToken
{
    const TOKEN = 'O';
    private $objectName;

    private $members;

    public function __construct($objectName, ArrayToken $members)
    {
        $this->objectName = $objectName;
        $this->members = $members;
    }

    public function getType()
    {
        return self::TOKEN;
    }

    public function setMember($i, AbstractToken $key, AbstractToken $value)
    {
        return $this->members->setMember($i, $key, $value);
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

