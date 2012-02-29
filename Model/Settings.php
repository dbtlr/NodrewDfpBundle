<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nodrew\Bundle\DfpBundle\Model;

/**
 * @package     NodrewDfpBundle
 * @author      Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class Settings extends TargetContainer
{
    protected $publisherId;

    /**
     * @param int $publisherId
     * @param array $targets
     */
    public function __construct($publisherId, array $targets = array())
    {
        $this->setPublisherId($publisherId);
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
}
