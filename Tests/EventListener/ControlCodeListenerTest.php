<?php

namespace Nodrew\Bundle\DfpBundle\Tests\EventListener;

use Nodrew\Bundle\DfpBundle\EventListener\ControlCodeListener,
    Symfony\Component\HttpFoundation\Response,
    Nodrew\Bundle\DfpBundle\Model\Collection;

class ControlCodeListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $event;
    protected function setUp()
    {
        $this->event = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Event\\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers Nodrew\Bundle\ExceptionalBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillNotModifyContentIfNoPlaceholder()
    {
        $content  = 'This is some awesome test content.';
        $response = new Response($content);
        
        $this->event->expects($this->once())
             ->method('getResponse')
             ->will($this->returnValue($response));        

        $listener = new ControlCodeListener(new Collection, '0000');
        $listener->onKernelResponse($this->event);

        $this->assertSame($content, $response->getContent());
    }

    /**
     * @covers Nodrew\Bundle\ExceptionalBundle\EventListener\ControlCodeListener::onKernelResponse
     */
    public function testResponseEventWillRemovePlaceHolderIfCollectionIsEmpty()
    {
        $content  = '<html><head><!-- NodrewDfpBundle Control Code --></head><body>This is some awesome test content.</body</html>';
        $result   = '<html><head></head><body>This is some awesome test content.</body</html>';
        $response = new Response($content);
        
        $this->event->expects($this->once())
             ->method('getResponse')
             ->will($this->returnValue($response));        

        $listener = new ControlCodeListener(new Collection, '0000');
        $listener->onKernelResponse($this->event);

        $this->assertSame($result, $response->getContent());
    }
}