<?php

namespace MVuoncino\Helper;

class NullToken extends AbstractToken
{
    const TOKEN = 'N';

    public function getType()
    {
        return self::TOKEN;
    }

    public function toArray()
    {
        return ['value' => null];
    }

    public function __toString()
    {
        return 'N;';
    }
}

