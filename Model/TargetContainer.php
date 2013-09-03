<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nodrew\Bundle\DfpBundle\Model;

/**
 * @package     NodrewDfpBundle
 * @author      Drew Butler <hi@dbtlr.com>
 * @copyright   (c) 2012 Drew Butler
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
abstract class TargetContainer
{
    protected $targets = array();

    /**
     * Set the targets
     *
     * @param array $targets
     */
    public function setTargets(array $targets)
    {
        foreach ($targets as $name => $value) {
            $this->addTarget($name, $value);
        }
    }

    /**
     * Add a target to the collection.
     *
     * @param string $name
     * @param string|int|array|null $value
     */
    public function addTarget($name, $value)
    {
        if (!is_string($name)) {
            throw new \LogicException('Cannot add a target with a value that is not a string.');
        }

        if (!is_int($value) && !is_array($value) && !is_null($value) && !is_string($value)) {
            throw new \LogicException('Cannot add a target with a value that is not an array, integer, string or null.');
        }

        $this->targets[$name] = $value;
    }

    /**
     * Get the targets
     *
     * @return array
     */
    public function getTargets()
    {
        return $this->targets;
    }    
}
