<?php

namespace MVuoncino\tests;

class TestObjectChild extends TestObject
{
    private $privateVarChild;

    protected $protectedVarChild;

    public $publicVarChild;

    public function setPrivateVarChild($privateVarChild)
    {
        $this->privateVarChild = $privateVarChild;
    }

    public function setProtectedVarChild($protectedVarChild)
    {
        $this->protectedVarChild = $protectedVarChild;
    }
}