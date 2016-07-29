<?php

namespace MVuoncino\Helper;

class NullToken extends AbstractToken
{
    const TOKEN = 'N';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TOKEN;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['value' => null];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'N;';
    }
}

