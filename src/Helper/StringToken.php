<?php

namespace MVuoncino\Helper;

class StringToken extends AbstractValueToken
{
    const TOKEN = 's';

    public function getType()
    {
        return self::TOKEN;
    }

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

