<?php

namespace MVuoncino\Helper;

class CustomObjectToken extends AbstractToken
{
    const TOKEN = 'C';

    public function getType()
    {
        return self::TOKEN;
    }

    private $objectName;

    private $serialized;

    public function __construct($objectName, $serialized)
    {
        $this->objectName = $objectName;
        $this->serialized = $serialized;
    }

    public function toArray()
    {
        return [
            'object' => $this->objectName,
            'serialized' => $this->serialized,
        ];
    }

    public function __toString()
    {
        return sprintf(
            "%s:%d:\"%s\":%d:{%s}",
            $this->getType(),
            strlen($this->objectName),
            $this->objectName,
            strlen($this->serialized),
            $this->serialized
        );
    }
}

