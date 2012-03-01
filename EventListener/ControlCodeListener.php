<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nodrew\Bundle\DfpBundle\EventListener;

use Nodrew\Bundle\DfpBundle\Model\Collection,
    Nodrew\Bundle\DfpBundle\Model\Settings,
    Nodrew\Bundle\DfpBundle\Model\AdUnit,
    Symfony\Component\HttpKernel\Event\FilterResponseEvent,
    Symfony\Component\HttpFoundation\Response;

/**
 * @package     NodrewDfpBundle
 * @author      Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class ControlCodeListener
{
    /**
     * The template placeholder where the DFP code is to be inserted.
     */
    const PLACEHOLDER = '<!-- NodrewDfpBundle Control Code -->';

    /**
     * @var Nodrew\Bundle\DfpBundle\Model\Collection
     */
    protected $collection;

    /**
     * @var Nodrew\Bundle\DfpBundle\Model\Settings
     */
    protected $settings;


    /**
     * Constructor.
     *
     * @param Nodrew\Bundle\DfpBundle\Model\Collection $collection
     * @param Nodrew\Bundle\DfpBundle\Model\Settings $settings
     */
    public function __construct(Collection $collection, Settings $settings)
    {
        $this->settings   = $settings;
        $this->collection = $collection;
    }

    /**
     * Switch out the Control Code placeholder for the Google DFP control code html,
     * based upon the included ads.
     *
     * @param Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $controlCode = '';
        if (count($this->collection) > 0) {
            $controlCode .= $this->getMainControlCode();
            
            foreach ($this->collection as $unit) {
                $controlCode .= $this->getAdControlBlock($unit);
            }
        }

        $response->setContent(str_replace(self::PLACEHOLDER, $controlCode, $response->getContent()));
    }

    /**
     * Get the main google dfp control code block.
     *
     * This inserts the main google script.
     *
     * @return string
     */
    protected function getMainControlCode()
    {
        return <<< CONTROL

<script type="text/javascript">
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') +
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();
</script>

CONTROL;
    }
    
    /**
     * Get the control block for an individual ad.
     *
     * @return string
     */
    protected function getAdControlBlock(AdUnit $unit)
    {
        $publisherId = $this->settings->getPublisherId();
        $targets     = $this->getTargetsBlock($unit->getTargets());
        $sizes       = $this->printSizes($unit->getSizes());
        $divId       = $unit->getDivId();
        $path        = $unit->getPath();

        return <<< BLOCK

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/{$publisherId}/{$path}', {$sizes}, '{$divId}').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();{$targets}
});
</script>

BLOCK;
    }
    
    /**
     * Print the sizes array in it's json equivalent.
     *
     * @param array $sizes
     * @return string
     */
    protected function printSizes(array $sizes)
    {
        if (count($sizes) == 1) {
            return '['.$sizes[0][0].', '.$sizes[0][1].']';
        }

        $string = '';
        foreach ($sizes as $size) {
            $string .= '['.$size[0].', '.$size[1].'], ';
        }
        
        return '['.trim($string, ', ').']';
    }
    
    /**
     * Get the targets block
     *
     * @return string
     */
    protected function getTargetsBlock(array $targets)
    {
        $block = '';
        
        foreach ($this->settings->getTargets() as $name => $target) {
            if (!array_key_exists($name, $targets)) {
                $targets[$name] = $target;
            }
        }
        
        
        foreach ($targets as $name => $target) {
            if ($target === null || $target === '') {
                continue;
            }
            
            if (is_array($target)) {
                $values = array_values($target);
                $target = '';
                foreach ($values as $value) {
                    $target .= "'$value',";
                }
                
                $target = trim($target, ',');

            } else {
                $target = "'$target'";
            }

            $block .= "\ngoogletag.target('$name', [$target]);";
        }

        return $block;
    }
}
