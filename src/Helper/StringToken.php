<?php

namespace MVuoncino\Helper;

class StringToken extends ValueToken
{
    public function __toString()
    {
        return sprintf(
            "%s:%d:\"%s\";",
            $this->getType(),
            strlen($this->getValue()),
            strval($this->getValue())
        );
    }
}

