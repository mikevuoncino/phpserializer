<?php

namespace MVuoncino\Tests;

//use \DateTime as PHPDateTime;
//use MVuoncino\Wrapper\DateTimeWrapper;
use MVuoncino\Serialization\Inspector as SafeUnserializer;
//use MVuoncino\tests\TestObject;
//use MVuoncino\tests\TestObjectChild;
use Mockery as M;
use PHPUnit_Framework_TestCase;

require "Objects/TestObject.php";
require "Objects/TestObjectChild.php";
require "Objects/TestObjectCustom.php";
require "Objects/ObserverTest.php";

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
            $this->assertEquals(['value' => $test], $parsed->toArray(), "");
            $this->assertEquals($x, (string)$parsed);
        }
    }

    public function testArrayParse()
    {
        //$this->markTestSkipped();
        $tests = [
            ['hello', 'world', 1, 2, 3, 4, -5, 6.7, 0],
            ['apple' => 'orange'],
        ];
        foreach ($tests as $test) {
            $x = serialize($test);
            $s = new SafeUnserializer($x);
            $tree = $s->parseSerializedData();
            //print_r($tree);
            //echo "\n=======\n" . strval($tree) . "\n======\n";
            $i = 0;
            foreach ($test as $key => $item) {
                //$this->assertEquals($key, $tree->toArray()['value'][$i]['key'], "");
                //$this->assertEquals($item, $tree->toArray()['value'][$i]['value'], "");
                ++$i;
            }
            $this->assertEquals($x, (string)$tree);
        }
    }

    public function testMultiArrayParse()
    {
        //$this->markTestSkipped();
        $tests = [
            ['first' => ['second' => ['third' => ['fourth' => 'fifth'], 'sixth' => ['seventh' => 'eighth']]]]
        ];
        foreach ($tests as $test) {
            $x = serialize($test);
            $s = new SafeUnserializer($x);
            $tree = $s->parseSerializedData();
            $this->assertEquals($x, (string)$tree);
        }
    }

    public function testObjectParse()
    {
        //$this->markTestSkipped();
        $object = new TestObject();
        $object->setPrivateVar('t1');
        $object->setProtectedVar('t2');
        $object->publicVar = 't3';

        $x = serialize($object);
        $s = new SafeUnserializer($x);
        $parsed = $s->parseSerializedData();

        //echo "serialized: $x\n";

        $this->assertEquals($x, (string)$parsed, "$x !==\n" .(string)$parsed);

        /*
        $tree = $parsed->toArray();
        $this->assertEquals('MVuoncino\tests\TestObject', $tree['value']['object']);
        $this->assertCount(3, $tree['value']['members']);
        //$this->assertEquals('MVuoncino\tests\TestObjectprivateVar', $tree[0]['value']['members'][0]['key']);
        $this->assertEquals('t1', $tree['value']['members'][0]['value']);
        //$this->assertEquals('*protectedVar', $tree[0]['value']['members'][1]['key']);
        $this->assertEquals('t2', $tree['value']['members'][1]['value']);
        //$this->assertEquals('publicVar', $tree[0]['value']['members'][2]['key']);
        $this->assertEquals('t3', $tree['value']['members'][2]['value']);
        */

    }

    public function testArrayObjectParse()
    {
        //$this->markTestSkipped();
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

        //echo "$x\n";

        $s = new SafeUnserializer($x);
        $parsed = $s->parseSerializedData();

        //echo "\n$x\n" .(string)$parsed . "\n";

        $this->assertEquals($x, (string)$parsed, "$x !==\n" .(string)$parsed);

        //print_r($tree);
    }

    public function testUnserializeFail()
    {
        //$this->markTestSkipped('for demo purposes');
        $x = 'O:8:"DateTime":0:{}';

        $s = new SafeUnserializer($x);
        $tree = $s->parseSerializedData()->toArray();
        //print_r($tree);

        //$this->assertEquals('DateTime', $tree['object']);
        //$this->assertCount(0, $tree['members']);
    }

    public function testCustomObjectParse()
    {
        //$this->markTestSkipped('for demo purposes');
        $customObject = new TestObjectCustom();
        $x = serialize($customObject);
        echo "$x\n";
        $s = new SafeUnserializer($x);
        $parsed = $s->parseSerializedData();

        $this->assertEquals($x, (string)$parsed, "$x !==\n" .(string)$parsed);
    }

    /*
    public function testConfabulator()
    {
        $mock = M::Mock('MVuoncino\Contracts\ConfabulatorInterface');
        $mock->shouldReceive('confabulate')->andReturn(true)->times(6);

        $x = serialize([1,2,3,4,5]);
        $s = new SafeUnserializer($x);
        $s->addConfabulator($mock);
        $tree = $s->parseSerializedData()->toArray();
    }
    */

    public function testObserver()
    {
        $observer = new \ObserverTest();

        $x = serialize(['mike','mike','mike','mike','mike']);
        $s = new SafeUnserializer($x);
        $s->attach($observer);
        $tree = $s->parseSerializedData()->toArray();

        foreach ($tree['members'] as $arr) {
            $this->assertEquals('vuoncino', $arr['value']['value']);
        }

        $z = unserialize((string)$s);
        $this->assertEquals(['vuoncino','vuoncino','vuoncino','vuoncino','vuoncino'], $z);
    }

    /*
    public function testDateTimeFail()
    {
        $x = new \DateTime(null);
        $y = serialize($x);
        echo $y;
        echo "$y\n";
    }
    */


}