<?php

namespace MVuoncino\Helper;

class ReferenceToken extends AbstractValueToken
{
    const TOKEN = 'R';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }
}

