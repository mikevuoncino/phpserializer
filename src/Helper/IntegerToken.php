<?php

namespace MVuoncino\Helper;

class IntegerToken extends AbstractValueToken
{
    const TOKEN = 'i';

    public function getType()
    {
        return self::TOKEN;
    }
}

