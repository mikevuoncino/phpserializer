<?php

namespace MVuoncino\Helper;

class IntegerToken extends AbstractValueToken
{
    const TOKEN = 'i';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }
}

