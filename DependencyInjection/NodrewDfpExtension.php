<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nodrew\Bundle\DfpBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @package     NodrewDfpBundle
 * @author      Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class NodrewDfpExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);
        $loader->load('services.xml');
        
        $this->setConfig($config, $container);
    }

    /**
     * Set the config options.
     *
     * @param array $config
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function setConfig($config, $container)
    {
        foreach (array('publisher_id', 'targets') as $attribute) {
            if (isset($config[$attribute])) {
                if ($attribute == 'targets') {
                    if (!is_array($config[$attribute])) {
                        $ex = new InvalidConfigurationException('Configuration for nodrew_dfp.targets must be an array.');
                        $ex->setPath('nodrew_dfp.targets');

                        throw $ex;
                    }
                }

                $container->setParameter('nodrew.dfp.'.$attribute, $config[$attribute]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://www.nodrew.com/schema/dic/dfp_bundle';
    }
}
