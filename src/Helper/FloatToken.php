<?php

namespace MVuoncino\Helper;

class FloatToken extends AbstractValueToken
{
    const TOKEN = 'd';

    public function getType()
    {
        return self::TOKEN;
    }
}

