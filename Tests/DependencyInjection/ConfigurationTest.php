<?php

namespace Nodrew\Bundle\DfpBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Nodrew\Bundle\DfpBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Nodrew\Bundle\ExceptionalBundle\DependencyInjection\Configuration::getConfigTree
     */
    public function testThatCanGetConfigTree()
    {
        $configuration = new Configuration();
        $this->assertInstanceOf('Symfony\Component\Config\Definition\NodeInterface', $configuration->getConfigTree());
    }
}