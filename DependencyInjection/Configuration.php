<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nodrew\Bundle\DfpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @package     NodrewDfpBundle
 * @author      Drew Butler <hi@dbtlr.com>
 * @copyright   (c) 2012 Drew Butler
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class Configuration
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nodrew_dfp', 'array');

        $rootNode
            ->children()
                ->scalarNode('publisher_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('div_class')->defaultValue('dfp-ad-unit')->end()
                ->variableNode('targets')->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}

