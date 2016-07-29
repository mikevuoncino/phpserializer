<?php

namespace MVuoncino\Helper;

class ArrayToken extends AbstractToken
{
    const TOKEN = 'a';

    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var array
     */
    private $values = [];

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }

    /**
     * @param AbstractToken $key
     * @param AbstractToken $value
     */
    public function addToken(AbstractToken $key, AbstractToken $value)
    {
        $this->keys[] = $key;
        $this->values[] = $value;
    }

    /**
     * @param int $i
     * @param AbstractToken $key
     * @param AbstractToken $value
     */
    public function setMember($i, AbstractToken $key, AbstractToken $value)
    {
        $this->keys[$i] = $key;
        $this->values[$i] = $value;
    }

    /**
     * @return int
     */
    public function getKeyValueCount()
    {
        return count($this->keys);
    }

    /**
     * @return string
     */
    public function getKeyValuePairString()
    {
        $serialized = "";
        foreach ($this->keys as $i => $key) {
            $serialized .=
                $this->keys[$i]->__toString() .
                $this->values[$i]->__toString();
        }
        return $serialized;
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->keys as $i => $key) {
            $array[] = [
                'key' => $key->toArray(),
                'value' => $this->values[$i]->toArray()
            ];
        }
       
        return ['members' => $array];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%s:%d:{%s}",
            $this->getType(),
            count($this->keys),
            $this->getKeyValuePairString()
        );
    }
}

