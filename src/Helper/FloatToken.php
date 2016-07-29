<?php

namespace MVuoncino\Helper;

class FloatToken extends AbstractValueToken
{
    const TOKEN = 'd';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }
}

