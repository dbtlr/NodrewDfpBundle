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
class AdUnit extends TargetContainer
{
    protected $path;
    protected $width;
    protected $height;
    protected $divId;
    protected $targets = array();

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     */
    public function __construct($path, $width, $height)
    {
        $this->setPath($path);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * Get the path.
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the width.
     *
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get the width.
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get the height.
     *
     * @param string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get the height.
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get the divId.
     *
     * @param string $divId
     */
    public function setDivId($divId)
    {
        $this->divId = $divId;
    }

    /**
     * Get the divId.
     *
     * @return string
     */
    public function getDivId()
    {
        return $this->divId;
    }
}
