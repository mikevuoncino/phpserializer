<?php

namespace MVuoncino\Helper;

abstract class AbstractToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    final public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @return string
     */
    abstract public function __toString();
}

