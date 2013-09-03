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
class Settings extends TargetContainer
{
    protected $publisherId;
    protected $divClass;

    /**
     * @param int $publisherId
     * @param int $divClass
     * @param array $targets
     */
    public function __construct($publisherId, $divClass, array $targets = array())
    {
        $this->setPublisherId($publisherId);
        $this->setDivClass($divClass);
        $this->setTargets($targets);
    }

    /**
     * Get the publisher id.
     *
     * @return string
     */
    public function getPublisherId()
    {
        return $this->publisherId;
    }

    /**
     * Set the publisher id.
     *
     * @param string publisherId
     */
    public function setPublisherId($publisherId)
    {
        $this->publisherId = $publisherId;
    }

    /**
     * Get the divClass.
     *
     * @return string
     */
    public function getDivClass()
    {
        return $this->divClass;
    }

    /**
     * Set the divClass.
     *
     * @param string divClass
     */
    public function setDivClass($divClass)
    {
        $this->divClass = $divClass;
    }
}
