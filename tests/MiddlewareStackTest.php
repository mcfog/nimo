<?php namespace Nimo;

use Prophecy\Prophet;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/12
 */
class MiddlewareStackTest extends \PHPUnit_Framework_TestCase
{

    public function testAppend()
    {
        $dummies = $this->makeDummies();

        $stack = new MiddlewareStack();
        $stack->append($dummies['middleware1']);
        $stack->append($dummies['middleware2']);

        $returnValue = $stack($dummies['reqDummy'], $dummies['resDummy'], $dummies['next']);

        $this->assertSame($dummies['resDummy4'], $returnValue);
    }

    public function testPrepend()
    {
        $dummies = $this->makeDummies();

        $stack = new MiddlewareStack();
        $stack->prepend($dummies['middleware2']);
        $stack->prepend($dummies['middleware1']);

        $returnValue = $stack($dummies['reqDummy'], $dummies['resDummy'], $dummies['next']);

        $this->assertSame($dummies['resDummy4'], $returnValue);
    }

    protected function makeDummies()
    {
        $prophet = new Prophet();

        $reqProphecy = $prophet->prophesize(ServerRequestInterface::class);
        $resProphecy = $prophet->prophesize(ResponseInterface::class);

        $reqDummy = $reqProphecy->reveal();
        $reqDummy2 = $reqProphecy->reveal();
        $reqDummy3 = $reqProphecy->reveal();
        $resDummy = $resProphecy->reveal();
        $resDummy2 = $resProphecy->reveal();
        $resDummy3 = $resProphecy->reveal();
        $resDummy4 = $resProphecy->reveal();

        $middleware1 = function (
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) use (
            $reqDummy,
            $reqDummy2,
            $resDummy,
            $resDummy2
        ) {
            $this->assertSame($resDummy, $res);
            $this->assertSame($reqDummy, $req);

            return $next($reqDummy2, $resDummy2);
        };

        $middleware2 = function (
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) use (
            $reqDummy2,
            $reqDummy3,
            $resDummy2,
            $resDummy3
        ) {
            $this->assertSame($resDummy2, $res);
            $this->assertSame($reqDummy2, $req);

            return $next($reqDummy3, $resDummy3);
        };

        $next = function (
            ServerRequestInterface $req,
            ResponseInterface $res
        ) use (
            $reqDummy3,
            $resDummy3,
            $resDummy4
        ) {
            $this->assertSame($resDummy3, $res);
            $this->assertSame($reqDummy3, $req);

            return $resDummy4;
        };

        return get_defined_vars();
    }
}
