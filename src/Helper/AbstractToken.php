<?php

namespace MVuoncino\Helper;

abstract class AbstractToken
{
    private $token;

    private $type;

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    final public function getToken()
    {
        return $this->token;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    final public function getType()
    {
        return $this->type;
    }

    abstract function toArray();

    public function __toString()
    {
        return sprintf("%s:", $this->type);
    }
}

