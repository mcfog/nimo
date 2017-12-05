<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Interop\Http\Server\RequestHandlerInterface;
use Nimo\AbstractMiddleware;
use Nimo\Middlewares\ConditionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConditionMiddlewareTest extends NimoTestCase
{
    public function testFalsyCondition()
    {
        $inner = $this->prophesize(AbstractMiddleware::class);
        $inner->__call('main', [])->shouldNotBeCalled();
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();
        $handler = $this->assertedHandler($request, $response);

        $condition = function (
            ServerRequestInterface $req,
            RequestHandlerInterface $hdl
        ) use ($request, $handler) {
            $this->assertSame($request, $req);
            $this->assertSame($handler, $hdl);

            return false;
        };

        $middleware = new ConditionMiddleware($condition, $inner->reveal());


        $returnValue = $middleware->process(
            $request,
            $handler
        );

        $this->assertSame($response, $returnValue);
    }

    public function testTruthyCondition()
    {
        $answerRes = $this->getMockForAbstractClass(ResponseInterface::class);
        $request = $this->getRequestMock();
        $expectedHandler = $this->throwHandler();
        $inner = $this->assertedMiddleware($request, $expectedHandler, $answerRes);

        $condition = function () {
            return true;
        };
        $middleware = new ConditionMiddleware($condition, $inner);


        $returnValue = $middleware->process(
            $request,
            $expectedHandler
        );

        $this->assertSame($answerRes, $returnValue);
    }
}
