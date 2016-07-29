<?php

namespace MVuoncino\Helper;

class CustomObjectToken extends AbstractToken
{
    const TOKEN = 'C';

    /**
     * @var string
     */
    private $objectName;

    /**
     * @var string 
     */
    private $serialized;
    
    /**
     * @param string $objectName
     * @param string $serialized
     */
    public function __construct($objectName, $serialized)
    {
        $this->objectName = $objectName;
        $this->serialized = $serialized;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'object' => $this->objectName,
            'serialized' => $this->serialized,
        ];
    }

    /**
     * @return string
     */
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

