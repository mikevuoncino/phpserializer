<?php

namespace MVuoncino\Helper;

class BooleanToken extends AbstractValueToken
{
    const TOKEN = 'b';

    public function getType()
    {
        return self::TOKEN;
    }
}

