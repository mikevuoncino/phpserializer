<?php

namespace MVuoncino\Helper;

class ObjectToken extends AbstractToken
{
    const TOKEN = 'O';
    
    /**
     * @var string
     */
    private $objectName;

    private $members;

    /**
     * 
     * @param type $objectName
     * @param ArrayToken $members
     */
    public function __construct($objectName, ArrayToken $members)
    {
        $this->objectName = $objectName;
        $this->members = $members;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }
    
    /**
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }
    
    /**
     * @param string $objectName
     * @return self
     */
    public function setObjectName($objectName)
    {
        $this->objectName = $objectName;
        return $this;
    }

    /**
     * @param int $i
     * @param AbstractToken $key
     * @param AbstractToken $value
     * @return self
     */
    public function setMember($i, AbstractToken $key, AbstractToken $value)
    {
        return $this->members->setMember($i, $key, $value);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'object' => $this->objectName,
            'members' => $this->members->toArray()
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
            $this->members->getKeyValueCount(),
            $this->members->getKeyValuePairString()
        );
    }
}