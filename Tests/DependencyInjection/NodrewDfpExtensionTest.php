<?php

namespace Nodrew\Bundle\DfpBundle\Tests\DependencyInjection;

use Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension;

class NodrewDfpExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension::load
     */
    public function testLoadFailure()
    {
        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $extension = $this->getMockBuilder('Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension')
            ->getMock();

        $extension->load(array(array()), $container);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension:load
     */
    public function testWillLoadWithOnlyPublisherId()
    {
        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterBag = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterBag
            ->expects($this->any())
            ->method('add');

        $container
            ->expects($this->any())
            ->method('getParameterBag')
            ->will($this->returnValue($parameterBag));

        $configs = array(
            array('publisher_id' => 'asdasd'),
        );

        $extension = new NodrewDfpExtension();
        $extension->load($configs, $container);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension:load
     */
    public function testWillExplodeWithoutKey()
    {
        $this->setExpectedException('Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException');

        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterBag = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();
        
        
        $configs = array();
        $extension = new NodrewDfpExtension();
        $extension->load($configs, $container);
    }    
}
