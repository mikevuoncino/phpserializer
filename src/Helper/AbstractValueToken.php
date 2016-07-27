<?php

namespace MVuoncino\Helper;

abstract class AbstractValueToken extends AbstractToken
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function toArray()
    {
        return ['value' => $this->value];
    }

    public function __toString()
    {
        return sprintf(
            "%s:%s;",
            $this->getType(),
            strval($this->value)
        );
    }
}

