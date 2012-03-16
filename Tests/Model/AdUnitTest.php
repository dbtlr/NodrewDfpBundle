<?php

namespace Nodrew\Bundle\DfpBundle\Tests\Model;

use Nodrew\Bundle\DfpBundle\Model\AdUnit,
    Nodrew\Bundle\DfpBundle\Model\Settings;

class AdUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::buildDivId
     */
    public function testWillBuildDivId()
    {
        $unit = new AdUnit('path', array(300, 200));

        $this->assertSame('dfp-'.spl_object_hash($unit), $unit->getDivId());
    }
    
    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillTurnSingleSizeIntoArrayofSizes()
    {
        $unit = new AdUnit('path', array(300, 200));
        
        $this->assertSame(array(array(300, 200)), $unit->getSizes());
    }
    
    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillLeaveMultipleSizesAsArrayofSizes()
    {
        $unit = new AdUnit('path', array(array(300, 200), array(200, 300)));
        
        $this->assertSame(array(array(300, 200), array(200, 300)), $unit->getSizes());
    }
    
    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillWorkWhenStringSizeGivenIsNumber()
    {
        $unit = new AdUnit('path', array('300', '200'));
        $this->assertSame(array(array('300', '200')), $unit->getSizes());
    }
    
    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillAcceptAdUnitWithNullSize()
    {
        $unit = new AdUnit('path', null);
        $this->assertSame(null, $unit->getSizes());
    }
    
    /**
     * @expectedException Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillExplodeWhenInvalidSingleSizeGivenAsEmptyArray()
    {
        $unit = new AdUnit('path', array());
    }
    
    /**
     * @expectedException Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillExplodeWhenInvalidSingleSizeGivenAsArrayWithOneParam()
    {
        $unit = new AdUnit('path', array(200));
    }
    
    /**
     * @expectedException Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillExplodeWhenInvalidSingleSizeGivenAsArrayWithMoreTahnTwoParams()
    {
        $unit = new AdUnit('path', array(200, 300, 400));
    }
    
    /**
     * @expectedException Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillExplodeWhenInvalidMultpleSizesGiven()
    {
        $unit = new AdUnit('path', array(array(200, 300), 400));
    }
    
    /**
     * @expectedException Nodrew\Bundle\DfpBundle\Model\AdSizeException
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::setSizes
     */
    public function testWillExplodeWhenInvalidStringSizeGiven()
    {
        $unit = new AdUnit('path', array('string', 'string'));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::print
     */
    public function testWillBuildPrintProperly()
    {
        $unit  = new AdUnit('path', array(300, 200));
        $unit->setDivId('divId');

        $expected = <<< EXPECTED

<div id="divId" class="class" style="width:300px; height:200px;">
<script type="text/javascript">
googletag.cmd.push(function() { googletag.display('divId'); });
</script>
</div>
EXPECTED;

        $this->assertSame($expected, $unit->output(new Settings('0000', 'class')));
    }
}
