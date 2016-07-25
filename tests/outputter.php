<?php

namespace MVuoncino\Tests;

//use \DateTime as PHPDateTime;
use MVuoncino\Wrapper\DateTimeWrapper;
use MVuoncino\Wrapper\SafeUnserializer;
//use MVuoncino\tests\TestObject;
//use MVuoncino\tests\TestObjectChild;
use PHPUnit_Framework_TestCase;

require "Objects/TestObject.php";
require "Objects/TestObjectChild.php";

class DateTimeTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleParseData()
    {
        //$this->markTestSkipped();
        $tests = [
            27,
            0,
            1,
            null,
            15,
            3.1415927,
            1234129843234,
            PHP_INT_MAX,
            -312434,
            -1,
            "hellothere",
            "mike \"the coder\" vuoncino",
            "that's what she said",
            "the quick brown fox jumped over the lazy dog",
            "\"\"\":;:;:;:;:;:;;;::;;:;;;\"\"\"",
        ];
        foreach ($tests as $test) {
            $x = serialize($test);
            $s = new SafeUnserializer($x);
            $parsed = $s->parseSerializedData();
            //$this->assertEquals(['value' => $test], $parsed, "");
            print_r($parsed);
        }
    }

    public function testArrayParse()
    {
        $this->markTestSkipped();
        $tests = [
            ['hello', 'world', 1, 2, 3, 4, -5, 6.7, 0],
            ['apple' => 'orange'],
        ];
        foreach ($tests as $test) {
            $x = serialize($test);
            $s = new SafeUnserializer($x);
            $tree = $s->parseSerializedData();
            print_r($tree);
            $i = 0;
            foreach ($test as $key => $item) {
                $this->assertEquals($key, $tree['value'][$i]['key'], "");
                $this->assertEquals($item, $tree['value'][$i]['value'], "");
                ++$i;
            }
        }
    }

    public function testMultiArrayParse()
    {
        $this->markTestSkipped();
        $tests = [
            ['first' => ['second' => ['third' => ['fourth' => 'fifth'], 'sixth' => ['seventh' => 'eighth']]]]
        ];
        foreach ($tests as $test) {
            $x = serialize($test);
            $s = new SafeUnserializer($x);
            $tree = $s->parseSerializedData();
        }
    }

    public function testObjectParse()
    {
        $this->markTestSkipped();
        $object = new TestObject();
        $object->setPrivateVar('t1');
        $object->setProtectedVar('t2');
        $object->publicVar = 't3';

        $x = serialize($object);
        $s = new SafeUnserializer($x);
        $tree = $s->parseSerializedData();

        $this->assertEquals('MVuoncino\tests\TestObject', $tree['value']['object']);
        $this->assertCount(3, $tree['value']['members']);
        //$this->assertEquals('MVuoncino\tests\TestObjectprivateVar', $tree[0]['value']['members'][0]['key']);
        $this->assertEquals('t1', $tree['value']['members'][0]['value']);
        //$this->assertEquals('*protectedVar', $tree[0]['value']['members'][1]['key']);
        $this->assertEquals('t2', $tree['value']['members'][1]['value']);
        //$this->assertEquals('publicVar', $tree[0]['value']['members'][2]['key']);
        $this->assertEquals('t3', $tree['value']['members'][2]['value']);

    }

    public function testArrayObjectParse()
    {
        $this->markTestSkipped();
        $object = new TestObject();
        $object->setPrivateVar('t1');
        $object->setProtectedVar('t2');
        $object->publicVar = 't3';

        $objectChild = new TestObjectChild();
        $objectChild->setPrivateVarChild('t4');
        $objectChild->setProtectedVarChild('t5');
        $objectChild->publicVarChild = $object;

        $testArray = ['one' => 'two', 'three' => $objectChild];

        $x = serialize($testArray);

        echo "$x\n";

        $s = new SafeUnserializer($x);
        $tree = $s->parseSerializedData();

        print_r($tree);
    }

    public function testUnserializeFail()
    {
        $this->markTestSkipped('for demo purposes');
        $x = 'O:8:"DateTime":0:{}';

        $s = new SafeUnserializer($x);
        $tree = $s->parseSerializedData();

        $this->assertEquals('DateTime', $tree['value']['object']);
        $this->assertCount(0, $tree['value']['members']);
    }

    /*
    public function testUnserializeFail()
    {
        $this->markTestSkipped('for demo purposes');
        $str = 'O:8:"DateTime":0:{}';
        $z = unserialize($str);
        echo "datetime:";
        var_export($z);
        echo "=====";
    }

    public function testCreateClass()
    {
        $date = new DateTimeWrapper();
        print_r($date);
    }

    public function testSerializeClass()
    {
        $date = new DateTimeWrapper();
        $str = serialize($date);
        echo "$str\n";
    }

    public function testUnserializeSuccess()
    {
        $str = 'O:33:"MVuoncino\Wrapper\DateTimeWrapper":0:{}';
        $z = unserialize($str);
        var_export($z);
    }
    */
}