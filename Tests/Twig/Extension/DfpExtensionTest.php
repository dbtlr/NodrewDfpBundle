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
        $this->settings   = new Settings('0000');
        $this->collection = new Collection;
        $this->extension  = new DfpExtension($this->settings, $this->collection);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateAdUnitAndReturnIt()
    {
        $path   = 'test';
        $width  = 200;
        $height = 300;
        $unit   = $this->extension->addAdUnit($path, $width, $height);
        
        $this->assertInstanceOf('Nodrew\Bundle\DfpBundle\Model\AdUnit', $unit);
        $this->assertEquals($path, $unit->getPath());
        $this->assertEquals($width, $unit->getWidth());
        $this->assertEquals($height, $unit->getHeight());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateAdUnitAndAddItToCollection()
    {
        $path = 'test';
        $width = 200;
        $height = 300;
        $this->extension->addAdUnit($path, $width, $height);
        
        $unit = $this->collection->first();
        $this->assertEquals($path, $unit->getPath());
        $this->assertEquals($width, $unit->getWidth());
        $this->assertEquals($height, $unit->getHeight());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateMultipleAdUnitsAndAddThemToCollection()
    {
        $units = array(
            array('path' => 'test', 'width' => 200, 'height' => 300),
            array('path' => 'test2', 'width' => 300, 'height' => 400),
            array('path' => 'test3', 'width' => 400, 'height' => 500),
        );
        
        foreach ($units as $unit) {
            $this->extension->addAdUnit($unit['path'], $unit['width'], $unit['height']);
        }
        
        $unit = $this->collection->first();
        $this->assertEquals($units[0]['path'], $unit->getPath());
        $this->assertEquals($units[0]['width'], $unit->getWidth());
        $this->assertEquals($units[0]['height'], $unit->getHeight());
        
        $unit = $this->collection->next();
        $this->assertEquals($units[1]['path'], $unit->getPath());
        $this->assertEquals($units[1]['width'], $unit->getWidth());
        $this->assertEquals($units[1]['height'], $unit->getHeight());
        
        $unit = $this->collection->next();
        $this->assertEquals($units[2]['path'], $unit->getPath());
        $this->assertEquals($units[2]['width'], $unit->getWidth());
        $this->assertEquals($units[2]['height'], $unit->getHeight());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Twig\Extension\DfpExtension::addAdUnit
     */
    public function testWillCreateAdUnitWithTarget()
    {
        $path = 'test';
        $width = 200;
        $height = 300;
        $targets = array('blah' => 'blah');
        $this->extension->addAdUnit($path, $width, $height, $targets);
        
        $unit = $this->collection->first();
        $this->assertEquals($path, $unit->getPath());
        $this->assertEquals($width, $unit->getWidth());
        $this->assertEquals($height, $unit->getHeight());
        $this->assertEquals($targets, $unit->getTargets());
    }

}
