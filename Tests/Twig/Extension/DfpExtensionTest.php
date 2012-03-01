<?php

namespace Nodrew\Bundle\DfpBundle\Tests\Twig\Extension;

use Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension,
    Nodrew\Bundle\DfpBundle\Model\AdUnit,
    Nodrew\Bundle\DfpBundle\Model\Settings,
    Nodrew\Bundle\DfpBundle\Model\Collection;

class DfpExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $settings;
    protected $extension;
    protected $collection;
    
    protected function setUp()
    {
        $this->settings   = new Settings('0000', 'class');
        $this->collection = new Collection;
        $this->extension  = new DfpExtension($this->settings, $this->collection);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateAdUnitAndAddItToCollection()
    {
        $path = 'test';
        $size = array(200, 300);
        $this->extension->addAdUnit($path, $size);
        
        $unit = $this->collection->first();
        $this->assertEquals($path, $unit->getPath());
        $this->assertEquals(array($size), $unit->getSizes());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateMultipleAdUnitsAndAddThemToCollection()
    {
        $units = array(
            array('path' => 'test', 'size' => array(200, 300)),
            array('path' => 'test2', 'size' => array(300, 400)),
            array('path' => 'test3', 'size' => array(400, 500)),
        );
        
        foreach ($units as $unit) {
            $this->extension->addAdUnit($unit['path'], $unit['size']);
        }
        
        $unit = $this->collection->first();
        $this->assertEquals($units[0]['path'], $unit->getPath());
        $this->assertEquals(array($units[0]['size']), $unit->getSizes());
        
        $unit = $this->collection->next();
        $this->assertEquals($units[1]['path'], $unit->getPath());
        $this->assertEquals(array($units[1]['size']), $unit->getSizes());
        
        $unit = $this->collection->next();
        $this->assertEquals($units[2]['path'], $unit->getPath());
        $this->assertEquals(array($units[2]['size']), $unit->getSizes());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateAdUnitWithTarget()
    {
        $path    = 'test';
        $size    = array(200, 300);
        $targets = array('blah' => 'blah');
        $this->extension->addAdUnit($path, $size, $targets);
        
        $unit = $this->collection->first();
        $this->assertEquals($path, $unit->getPath());
        $this->assertEquals(array($size), $unit->getSizes());
        $this->assertEquals($targets, $unit->getTargets());
    }

}
