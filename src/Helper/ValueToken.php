<?php

namespace MVuoncino\Helper;

class ValueToken extends AbstractToken
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
        return parent::__toString() . sprintf(
            "%s;",
            strval($this->value)
        );
    }
}

