<?php

namespace MVuoncino\Serialization;

use MVuoncino\Exceptions\ParseException;
use MVuoncino\Helper\AbstractToken;
use MVuoncino\Helper\IntegerToken;
use MVuoncino\Helper\FloatToken;
use MVuoncino\Helper\BooleanToken;
use MVuoncino\Helper\ReferenceToken;
use MVuoncino\Helper\NullToken;
use MVuoncino\Helper\StringToken;
use MVuoncino\Helper\ArrayToken;
use MVuoncino\Helper\ObjectToken;
use MVuoncino\Helper\CustomObjectToken;
use \SplSubject;
use \SplObserver;
use \SplObjectStorage;

class Inspector implements SplSubject
{
    const PARSE_ERROR = 'Could not understand character: ';

    /**
     * @var string
     */
    private $str;

    /**
     * @var string
     */
    private $parsed;

    /**
     * @var SplObjectStorage
     */
    private $observers;

    /**
     * @var AbstractToken
     */
    private $_lastToken = null;

    /**
     * @var string
     */
    private $_strParse;

    /**
     * @var int
     */
    private $_ptrParse;

    /**
     * @param string $str
     */
    public function __construct($str)
    {
        $this->observers = new SplObjectStorage();
        $this->str = $str;
    }

    /**
     * @param SplObserver $observer
     * @return self
     */
    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
        return $this;
    }
   
    /**
     * @param SplObserver $observer
     * @return self
     */
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
        return $this;
    }

    /**
     * 
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @return AbstractToken
     */
    public function parseSerializedData()
    {
        $this->_ptrParse = 0;
        $this->_strParse = $this->str; // make a copy
        $this->parsed = $this->catchType();
        return $this->parsed;
    }

    /**
     * @return AbstractToken
     */
    public function getLastToken()
    {
        return $this->_lastToken;
    }

    /**
     * @param AbstractToken $token
     * @return self
     */
    public function setLastToken(AbstractToken $token)
    {
        $this->_lastToken = $token;
        return $this;
    }

    /**
     * @return AbstractToken
     * @throws ParseException
     */
    protected function catchType()
    {
        $beginPtr = $this->position();

        $type = $this->slurp(1);
        
        switch ($type) {
            case NullToken::TOKEN: // N;
                $this->slurp(1);
                $this->_lastToken = new NullToken();
                break;
            case BooleanToken::TOKEN: // x:<value>;
            case IntegerToken::TOKEN:
            case FloatToken::TOKEN:
            case ReferenceToken::TOKEN:
                $this->slurp(1);
                $this->_lastToken = $this->catchValue($type);
                break;
            case StringToken::TOKEN:
                $this->slurp(1);
                $this->_lastToken = $this->catchString();
                break;
            case ArrayToken::TOKEN:
                $this->slurp(1);
                $this->_lastToken = $this->catchArray();
                break;
            case CustomObjectToken::TOKEN:
                $this->slurp(1);
                $this->_lastToken = $this->catchCustomObject();
                break;
            case ObjectToken::TOKEN:
                $this->slurp(1);
                $this->_lastToken = $this->catchObject();
                break;
            default:
                throw new ParseException(self::PARSE_ERROR . $type);
        }
        //$this->_lastToken->setType($type);
        $this->_lastToken->setToken(substr($this->str, $beginPtr, $this->position() - $beginPtr));
        $this->notify();
        return $this->_lastToken;
    }

    /**
     * @param string $type
     * @return FloatToken|BooleanToken|IntegerToken
     */
    protected function catchValue($type)
    {
        $value = $this->find(';');
        $this->slurp(1); // ;
        switch ($type) {
            case BooleanToken::TOKEN:
                return new BooleanToken($value);
            case IntegerToken::TOKEN:
                return new IntegerToken($value);
            case FloatToken::TOKEN:
                return new FloatToken($value);
            case ReferenceToken::TOKEN:
                return new ReferencetToken($value);
        }
    }

    /**
     * @return string
     */
    protected function catchLengthContent()
    {
        $length = $this->find(':');
        $this->slurp(2); // : and {
        $value = $this->slurp($length);
        $this->slurp(1); // }
        return $value;
    }

    /**
     * @return StringToken
     */
    protected function catchString()
    {
        $length = $this->find(':');
        $this->slurp(1); // :
        $value = $this->slurp($length + 2); // +2 to account for double quotes 
        $this->slurp(1); // ;
        return new StringToken(substr($value, 1, -1));
    }

    /**
     * @return ArrayToken
     */
    protected function catchArray()
    {
        $elements = $this->find(':');
        $this->slurp(2); // : and {
        $array = new ArrayToken();
        for ($i = 0; $i < $elements; ++$i) {
            $array->addToken(
                $this->catchType(),
                $this->catchType()
            );
        }
        $this->slurp(1);
        return $array;
    }

    /**
     * @return ObjectToken
     */
    protected function catchObject()
    {
        $objectName = $this->catchString()->getValue();
        $objectParsed = $this->catchArray();
        return new ObjectToken($objectName, $objectParsed);
    }

    /**
     * @return CustomObjectToken
     */
    protected function catchCustomObject()
    {
        $objectName = $this->catchString()->getValue();
        $serialized = $this->catchLengthContent();
        return new CustomObjectToken($objectName, $serialized);
    }

    /**
     * @return int
     */
    protected function position()
    {
        return $this->_ptrParse;
    }

    /**
     * @param string $char
     * @return string
     */
    protected function find($char)
    {
        return $this->slurp(strpos($this->_strParse, $char));
    }

    /**
     * @param int $length
     * @return string
     */
    protected function slurp($length)
    {
        $part = substr($this->_strParse, 0, $length);
        $this->_strParse = substr($this->_strParse, $length);
        $this->_ptrParse += $length;
        return $part;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->parsed;
    }
}