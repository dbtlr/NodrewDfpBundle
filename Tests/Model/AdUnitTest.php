<?php

namespace Nodrew\Bundle\DfpBundle\Tests\Model;

use Nodrew\Bundle\DfpBundle\Model\AdUnit;

class AdUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::buildDivId
     */
    public function testWillBuildDivId()
    {
        $unit = new AdUnit('path', 300, 200);

        $this->assertSame('dfp-'.spl_object_hash($unit), $unit->getDivId());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\Model\AdUnit::__toString
     */
    public function testWillBuildPrintProperly()
    {
        $unit  = new AdUnit('path', 300, 200);
        $unit->setDivId('divId');

        $expected = <<< EXPECTED

<div id="divId" style="width:300px; height:200px;">
<script type="text/javascript">
googletag.cmd.push(function() { googletag.display('divId'); });
</script>
</div>
EXPECTED;

        $this->assertSame($expected, $unit->__toString());
    }
}
