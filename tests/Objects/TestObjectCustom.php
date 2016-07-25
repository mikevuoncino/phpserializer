<?php

namespace MVuoncino\tests;

class TestObjectCustom implements \Serializable
{
    public function serialize()
    {
        return "custom";
    }

    public function unserialize($data)
    {
        // nothing
    }
}