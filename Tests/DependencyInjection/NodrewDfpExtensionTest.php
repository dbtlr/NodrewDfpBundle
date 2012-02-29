<?php

namespace Nodrew\Bundle\DfpBundle\Tests\DependencyInjection;

use Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension,
    Symfony\Component\DependencyInjection\ContainerBuilder;

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
        $container = new ContainerBuilder();

        $configs = array(
            array('publisher_id' => 'asdasd'),
        );

        $extension = new NodrewDfpExtension();
        $extension->load($configs, $container);

        $this->assertSame('asdasd', $container->getParameter('nodrew.dfp.publisher_id'));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @covers Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension:load
     */
    public function testWillExplodeWithoutKey()
    {
        $container = new ContainerBuilder;

        $configs = array();
        $extension = new NodrewDfpExtension();
        $extension->load($configs, $container);
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension:load
     */
    public function testWillSetTargetsArray()
    {
        $container = new ContainerBuilder();

        $configs = array(
            array('publisher_id' => 'asdasd'),
            array('targets'      => array('name1' => 'value1')),
        );

        $extension = new NodrewDfpExtension();
        $extension->load($configs, $container);

        $this->assertSame(array('name1' => 'value1'), $container->getParameter('nodrew.dfp.targets'));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @covers Nodrew\Bundle\DfpBundle\DependencyInjection\NodrewDfpExtension:load
     */
    public function testWillExplodeIfTargetIsNotAnArray()
    {
        $container = new ContainerBuilder();

        $configs = array(
            array('publisher_id' => 'asdasd'),
            array('targets'      => 'not an array'),
        );

        $extension = new NodrewDfpExtension();
        $extension->load($configs, $container);

        $this->assertSame(array('name1' => 'value1'), $container->getParameter('nodrew.dfp.targets'));
    }
}
