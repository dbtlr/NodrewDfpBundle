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
            'dfp_ad_unit'     => new \Twig_Filter_Method($this, 'addAdUnit', array('is_safe' => array('html'))),
            'dfp_oop_ad_unit' => new \Twig_Filter_Method($this, 'addOutOfPageAdUnit', array('is_safe' => array('html')))
        );
    }

    /**
     * Create an ad unit and return the source
     *
     * @param string $path
     * @param array $sizes
     * @param array $targets
     * @return string
     */
    public function addAdUnit($path, array $sizes, array $targets = array())
    {
        $unit = new AdUnit($path, $sizes, $targets);

        $this->collection->add($unit);
        
        return $unit->output($this->settings);
    }

    /**
     * Create an out of page ad unit and return the source
     *
     * @param string $path
     * @param array $targets
     * @return string
     */
    public function addOutOfPageAdUnit($path, array $targets = array())
    {
        $unit = new AdUnit($path, null, $targets);

        $this->collection->add($unit);
        
        return $unit->output($this->settings);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nodrew_dfp';
    }
}
