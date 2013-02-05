<?php

namespace Elastica\Test\Bulk;

use Elastica\Bulk\Action;
use Elastica\Bulk;
use Elastica\Client;
use Elastica\Exception\Bulk\ResponseException;
use Elastica\Index;
use Elastica\Test\Base as BaseTest;
use Elastica\Bulk\ResponseSet;
use Elastica\Response;
use Elastica\Type;

class ActionTest extends BaseTest
{
    public function testAction()
    {
        $action = new Action();
        $this->assertEquals('index', $action->getOpType());
        $this->assertFalse($action->hasSource());

        $expected = '{"index":[]}' . "\n";
        $this->assertEquals($expected, $action->toString());

        $action->setIndex('index');

        $expected = '{"index":{"_index":"index"}}' . "\n";
        $this->assertEquals($expected, $action->toString());

        $action->setType('type');

        $expected = '{"index":{"_index":"index","_type":"type"}}' . "\n";
        $this->assertEquals($expected, $action->toString());

        $action->setId(1);
        $expected = '{"index":{"_index":"index","_type":"type","_id":1}}' . "\n";
        $this->assertEquals($expected, $action->toString());

        $client = new Client();
        $index = new Index($client, 'index2');
        $type = new Type($index, 'type2');

        $action->setIndex($index);

        $expected = '{"index":{"_index":"index2","_type":"type","_id":1}}' . "\n";
        $this->assertEquals($expected, $action->toString());

        $action->setType($type);

        $expected = '{"index":{"_index":"index2","_type":"type2","_id":1}}' . "\n";
        $this->assertEquals($expected, $action->toString());

        $action->setSource(array('user' => 'name'));

        $expected = '{"index":{"_index":"index2","_type":"type2","_id":1}}' . "\n";
        $expected.= '{"user":"name"}' . "\n";

        $this->assertEquals($expected, $action->toString());
        $this->assertTrue($action->hasSource());

        $this->assertFalse(Action::isValidOpType('foo'));
        $this->assertTrue(Action::isValidOpType('delete'));
    }
}
