<?php

namespace MVuoncino\Helper;

class BooleanToken extends AbstractValueToken
{
    const TOKEN = 'b';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }
}

