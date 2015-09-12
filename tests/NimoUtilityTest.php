<?php namespace Nimo;

use Prophecy\Prophet;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/12
 */
class NimoUtilityTest extends \PHPUnit_Framework_TestCase
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
}
