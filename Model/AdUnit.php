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
    protected $sizes;
    protected $divId;
    protected $targets = array();

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     * @param array $targets
     */
    public function __construct($path, $sizes=array(), array $targets = array())
    {
        $this->setPath($path);
        $this->setSizes($sizes);
        $this->setTargets($targets);
        
        $this->buildDivId();
    }
    
    /**
     * Build the divId.
     */
    public function buildDivId()
    {
        $this->divId = 'dfp-'.spl_object_hash($this);
    }
    
    /**
     * Output the DFP code for this ad unit
     * 
     * @param Nodrew\Bundle\DfpBundle\Model\Settings $settings
     * @return string
     */
    public function output(Settings $settings)
    {
        $width  = $this->getLargestWidth();
        $height = $this->getLargestHeight();
        $class  = $settings->getDivClass();

        return <<< RETURN

<div id="{$this->divId}" class="{$class}" style="width:{$width}px; height:{$height}px;">
<script type="text/javascript">
googletag.cmd.push(function() { googletag.display('{$this->divId}'); });
</script>
</div>
RETURN;
    }
    
    /**
     * Get the largest width in the sizes.
     *
     * @return int
     */
    public function getLargestWidth()
    {
        $largest = 0;
        foreach ($this->sizes as $size) {
            if ($size[0] > $largest) {
                $largest = $size[0];
            }
        }
        
        return $largest;
    }
    
    /**
     * Get the largest height in the sizes.
     *
     * @return int
     */
    public function getLargestHeight()
    {
        $largest = 0;
        foreach ($this->sizes as $size) {
            if ($size[1] > $largest) {
                $largest = $size[1];
            }
        }
        
        return $largest;
    }
    
    /**
     * Fix the given sizes, if possible, so that they will match the internal array needs.
     *
     * @throws Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @param array|null$sizes
     * @return array|null
     */
    protected function fixSizes($sizes)
    {
        if ($sizes === null) {
            return;
        }
        
        if (count($sizes) == 0) {
            throw new AdSizeException('The size cannot be an empty array. It should be given as an array with a width and height. ie: array(800,600).');
        }

        if ($this->checkSize($sizes)) {
            return array($sizes);
        }
        
        foreach ($sizes as $size) {
            if (!$this->checkSize($size)) {
                throw new AdSizeException(sprintf('Cannot take the size: %s as a parameter. A size should be an array giving a width and a height. ie: array(800,600).', printf($size, true)));
            }
        }
        
        return $sizes;
    }
    
    /**
     * Check that the given size has is an array with two numeric elements.
     */
    protected function checkSize($size)
    {
        if (is_array($size) && count($size) == 2 && isset($size[0]) && is_numeric($size[0]) && isset($size[1]) && is_numeric($size[1])) {
            return true;
        }
        
        return false;
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
     * Get the sizes.
     *
     * @throws Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @param array $sizes
     */
    public function setSizes($sizes)
    {
        $this->sizes = $this->fixSizes($sizes);
    }

    /**
     * Get the sizes.
     *
     * @return array
     */
    public function getSizes()
    {
        return $this->sizes;
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
