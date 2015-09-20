<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/12
 */

use Nimo\NimoUtility;
use Prophecy\Prophet;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NimoUtilityTest extends NimoTestCase
{

    public function testNoopNext()
    {
        $prophet = new Prophet();

        $reqProphecy = $prophet->prophesize(ServerRequestInterface::class);
        $resProphecy = $prophet->prophesize(ResponseInterface::class);

        $req = $reqProphecy->reveal();
        $res = $resProphecy->reveal();

        /** @noinspection PhpParamsInspection */
        $returnValue = NimoUtility::noopNext($req, $res);

        $this->assertSame($res, $returnValue);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrap()
    {
        NimoUtility::wrap(null);
    }
}
