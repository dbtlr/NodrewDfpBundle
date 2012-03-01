<?php

namespace Nodrew\Bundle\DfpBundle\Tests\EventListener;

use Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener,
    Symfony\Component\HttpFoundation\Response,
    Nodrew\Bundle\DfpBundle\Model\AdUnit,
    Nodrew\Bundle\DfpBundle\Model\Settings,
    Nodrew\Bundle\DfpBundle\Model\Collection;

class ControlCodeListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $event;
    protected $listener;
    protected $settings;
    protected $collection;
    protected $response;

    protected function setUp()
    {
        $this->collection = new Collection;
        $this->settings   = new Settings('0000');
        $this->listener   = new ControlCodeListener($this->collection, $this->settings);
    }

    protected function event($content)
    {
        $this->response = new Response($content);

        $event = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Event\\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->once())
             ->method('getResponse')
             ->will($this->returnValue($this->response));

        return $event;
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillNotModifyContentIfNoPlaceholder()
    {
        $content  = 'This is some awesome test content.';

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($content, $this->response->getContent());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillRemovePlaceHolderIfCollectionIsEmpty()
    {
        $content  = '<html><head><!-- NodrewDfpBundle Control Code --></head><body>This is some awesome test content.</body</html>';
        $result   = '<html><head></head><body>This is some awesome test content.</body</html>';

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, $this->response->getContent());
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillLoadAdUnitFromCollection()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(300, 255)));
        $unit->setDivId('divId');
        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintMultipleSizes()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [[300, 255], [400, 355]], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(array(300, 255),array(400, 355))));
        $unit->setDivId('divId');
        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillLoadMultipleAdUnitsFromCollection()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId1').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path2', [400, 355], 'divId2').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path3', [500, 455], 'divId3').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>
RESPONSE;

        $this->collection->add($unit1 = new AdUnit('path/path', array(300, 255)));
        $this->collection->add($unit2 = new AdUnit('path/path2', array(400, 355)));
        $this->collection->add($unit3 = new AdUnit('path/path3', array(500, 455)));
        $unit1->setDivId('divId1');
        $unit2->setDivId('divId2');
        $unit3->setDivId('divId3');
        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }


    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintTargetsIntoAdUnit()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('SSS', ['blue']);
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(300, 255)));
        $unit->setDivId('divId');
        $unit->addTarget('SSS', 'blue');

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintMultipleTargetsIntoAdUnit()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('SSS', ['blue']);
googletag.target('TTT', ['green']);
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(300, 255)));
        $unit->setDivId('divId');
        $unit->addTarget('SSS', 'blue');
        $unit->addTarget('TTT', 'green');

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintTargetFromSettings()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('SSS', ['blue']);
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(300, 255)));
        $unit->setDivId('divId');
        $this->settings->addTarget('SSS', 'blue');

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintTargetFromSettingsAndMergeWithAdUnit()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('TTT', ['green']);
googletag.target('SSS', ['blue']);
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(300, 255)));
        $unit->setDivId('divId');
        $this->settings->addTarget('SSS', 'blue');
        $unit->addTarget('TTT', 'green');

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintTargetFromSettingsAndMergeWithMultipleAdUnits()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId1').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('TTT', ['green']);
googletag.target('SSS', ['blue']);
});
</script>

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path2', [400, 355], 'divId2').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('WWW', ['red']);
googletag.target('SSS', ['blue']);
});
</script>
RESPONSE;

        $this->collection->add($unit1 = new AdUnit('path/path', array(300, 255)));
        $this->collection->add($unit2 = new AdUnit('path/path2', array(400, 355)));
        $unit1->setDivId('divId1');
        $unit2->setDivId('divId2');

        $this->settings->addTarget('SSS', 'blue');
        $unit1->addTarget('TTT', 'green');
        $unit2->addTarget('WWW', 'red');

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }

    /**
     * @covers Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillPrintTargetArrayIntoAdUnit()
    {
        $content  = '<!-- NodrewDfpBundle Control Code -->';
        $result   = <<< RESPONSE
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

<script type="text/javascript">
googletag.cmd.push(function() {
googletag.defineSlot('/0000/path/path', [300, 255], 'divId').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
googletag.target('SSS', ['blue','green']);
});
</script>
RESPONSE;

        $this->collection->add($unit = new AdUnit('path/path', array(300, 255)));
        $unit->setDivId('divId');
        $unit->addTarget('SSS', array('blue', 'green'));

        $this->listener->onKernelResponse($this->event($content));

        $this->assertSame($result, trim($this->response->getContent()));
    }
}
