<?php

namespace MVuoncino\Helper;

abstract class AbstractValueToken extends AbstractToken
{
    /**
     * @var type 
     */
    private $value;

    /**
     * @param string|int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['value' => $this->value];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%s:%s;",
            $this->getType(),
            strval($this->value)
        );
    }
}

