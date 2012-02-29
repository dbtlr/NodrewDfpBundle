<?php

namespace Nodrew\Bundle\DfpBundle\Tests\Model;

use Nodrew\Bundle\DfpBundle\Model\TargetContainer;

class TargetContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $settings;

    protected function setUp()
    {
        $this->settings = new Settings();
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testCanAddTargetWithStringValue()
    {
        $this->settings->addTarget('name', 'value');

        $this->assertEquals(array('name' => 'value'), $this->settings->getTargets());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testCanAddTargetWithIntegerValue()
    {
        $this->settings->addTarget('name', 42);

        $this->assertEquals(array('name' => 42), $this->settings->getTargets());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testCanAddTargetWithNullValue()
    {
        $this->settings->addTarget('name', null);

        $this->assertEquals(array('name' => null), $this->settings->getTargets());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testCanAddTargetWithArrayValue()
    {
        $this->settings->addTarget('name', array(1,2,3));

        $this->assertEquals(array('name' => array(1,2,3)), $this->settings->getTargets());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testCanAddMultipleTargets()
    {
        $this->settings->addTarget('name1', 'value1');
        $this->settings->addTarget('name2', 'value2');

        $this->assertEquals(array('name1' => 'value1', 'name2' => 'value2'), $this->settings->getTargets());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     * @expectedException LogicException
     */
    public function testAddTargetWillExplodeWithObjectValue()
    {
        $this->settings->addTarget('name', new \stdClass);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     * @expectedException LogicException
     */
    public function testAddTargetWillExplodeWithBooleanValue()
    {
        $this->settings->addTarget('name', true);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testAddTargetCanAddMultipleFromArray()
    {
        $this->settings->setTargets(array(
            'name1' => 'value1',
            'name2' => 'value2',
        ));

        $this->assertEquals(array('name1' => 'value1', 'name2' => 'value2'), $this->settings->getTargets());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\Settings::addTarget
     */
    public function testAddTargetCanAddMultipleFromArrayMultipleTimesAndWillOverWriteKeysButLeaveUnSetKeys()
    {
        $this->settings->setTargets(array(
            'name1' => 'value1',
            'name2' => 'value2',
        ));

        $this->settings->setTargets(array(
            'name2' => 'value22',
            'name3' => 'value3',
        ));

        $this->assertEquals(array('name1' => 'value1', 'name2' => 'value22', 'name3' => 'value3'), $this->settings->getTargets());
    }
}

class Settings extends TargetContainer {}
