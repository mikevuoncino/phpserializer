<?php

namespace MVuoncino\Helper;

abstract class AbstractToken
{
    private $token;

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    final public function getToken()
    {
        return $this->token;
    }

    abstract public function getType();

    abstract public function toArray();

    abstract public function __toString();
}

