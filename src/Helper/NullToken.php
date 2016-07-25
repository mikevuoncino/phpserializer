<?php

namespace MVuoncino\Helper;

class NullToken extends AbstractToken
{
    public function toArray()
    {
        return ['value' => null];
    }

    public function __toString()
    {
        return 'N;';
    }
}

