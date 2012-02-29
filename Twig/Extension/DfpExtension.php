<?php

namespace Nodrew\Bundle\DfpBundle\Twig\Extension;

use Nodrew\Bundle\DfpBundle\Model\AdUnit,
    Nodrew\Bundle\DfpBundle\Model\Settings,
    Nodrew\Bundle\DfpBundle\Model\Collection;

class DfpExtension extends \Twig_Extension
{
    protected $settings;
    protected $collection;

    /**
     * @param Nodrew\Bundle\DfpBundle\Model\Settings $settings
     * @param Nodrew\Bundle\DfpBundle\Model\Collection $collection
     */
    public function __construct(Settings $settings, Collection $collection)
    {
        $this->settings   = $settings;
        $this->collection = $collection;
    }

    /**
     * Define the functions that are available to templates.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'dfp_ad_unit' => new \Twig_Filter_Method($this, 'addAdUnit')
        );
    }

    /**
     * Create an ad unit and return the source
     *
     * @param string $path
     * @param int $width
     * @param int $height
     * @param array $targets
     * @return Nodrew\Bundle\DfpBundle\Model\AdUnit
     */
    public function addAdUnit($path, $width, $height, array $targets = array())
    {
        $unit = new AdUnit($path, $width, $height, $targets);

        $this->collection->add($unit);
        
        return $unit;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nodrew_dfp';
    }
}
