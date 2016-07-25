<?php

namespace MVuoncino\tests;

class TestObject
{
    private $privateVar;

    protected $protectedVar;

    public $publicVar;

    public function setPrivateVar($privateVar)
    {
        $this->privateVar = $privateVar;
    }

    public function setProtectedVar($protectedVar)
    {
        $this->protectedVar = $protectedVar;
    }
}