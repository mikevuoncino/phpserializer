<?php

namespace MVuoncino\Serialization;

use MVuoncino\Exceptions\ParseException;
use MVuoncino\Helper\TokenFactory;
use MVuoncino\Helper\NullToken;
use MVuoncino\Helper\StringToken;
use MVuoncino\Helper\ValueToken;
use MVuoncino\Helper\ArrayToken;
use MVuoncino\Helper\ObjectToken;
use MVuoncino\Helper\CustomObjectToken;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerTrait;

class Inspector
{
    //use LoggerAwareTrait;
    //use LoggerTrait;
 
    private $factory;

    private $str;

    public function __construct($str, TokenFactory $factory = null)
    {
        $this->str = $str;
        $this->factory = $factory;
    }

    public function parseSerializedData()
    {
        $ptr = 0;
        $str = $this->str; // make a copy
        return $this->catchType($str, $ptr);
    }

    public function catchType(&$str, &$ptr)
    {
        $beginPtr = $ptr;

        $type = $this->slurp($str, $ptr, 1);
        
        switch ($type) {
            case 'N': // N;
                $this->slurp($str, $ptr, 1);
                $caught = new NullToken();
                break;
            case 'b': // x:<value>;
            case 'i':
            case 'd':
                $this->slurp($str, $ptr, 1);
                $caught = $this->catchValue($str, $ptr);
                break;
            case 's':
                $this->slurp($str, $ptr, 1);
                $caught = $this->catchString($str, $ptr);
                break;
            case 'a':
                $this->slurp($str, $ptr, 1);
                $caught = $this->catchArray($str, $ptr);
                break;
            case 'C':
                $this->slurp($str, $ptr, 1);
                $caught = $this->catchCustomObject($str, $ptr);
                break;
            case 'O':
                $this->slurp($str, $ptr, 1);
                $caught = $this->catchObject($str, $ptr);
                break;
            default:
                return false;
        }
        $caught->setType($type);
        $caught->setToken(substr($this->str, $beginPtr, $ptr - $beginPtr));
        return $caught;
    }

    public function catchValue(&$str, &$ptr)
    {
        $value = $this->find($str, $ptr, ';');
        $this->slurp($str, $ptr, 1); // ;
        return new ValueToken($value);
    }

    public function catchLengthContent(&$str, &$ptr)
    {
        $length = $this->find($str, $ptr, ':');
        $this->slurp($str, $ptr, 2); // : and {
        $value = $this->slurp($str, $ptr, $length);
        $this->slurp($str, $ptr, 1); // }
        return $value;
    }

    public function catchString(&$str, &$ptr)
    {
        $length = $this->find($str, $ptr, ':');
        $this->slurp($str, $ptr, 1); // :
        $value = $this->slurp($str, $ptr, $length + 2); // +2 to account for double quotes 
        $this->slurp($str, $ptr, 1); // ;
        return new StringToken(substr($value, 1, -1));
    }

    public function catchArray(&$str, &$ptr)
    {
        $elements = $this->find($str, $ptr, ':');
        $this->slurp($str, $ptr, 2); // : and {
        $array = new ArrayToken();
        for ($i = 0; $i < $elements; ++$i) {
            $array->addToken(
                $this->catchType($str, $ptr),
                $this->catchType($str, $ptr)
            );
        }
        $this->slurp($str, $ptr, 1);
        return $array;
    }

    public function catchObject(&$str, &$ptr)
    {
        $objectName = $this->catchString($str, $ptr)->getValue();
        $objectParsed = $this->catchArray($str, $ptr);
        return new ObjectToken($objectName, $objectParsed);
    }

    public function catchCustomObject(&$str, &$ptr)
    {
        $objectName = $this->catchString($str, $ptr)->getValue();
        $serialized = $this->catchLengthContent($str, $ptr);
        return new CustomObjectToken($objectName, $serialized);
    }

    public function find(&$str, &$ptr, $char)
    {
        return $this->slurp($str, $ptr, strpos($str, $char));
    }

    public function slurp(&$str, &$ptr, $length)
    {
        $part = substr($str, 0, $length);
        $str = substr($str, $length);
        $ptr += $length;
        return $part;
    }
}