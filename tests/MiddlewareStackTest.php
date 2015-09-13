<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/12
 */

use Nimo\AbstractMiddleware;
use Nimo\IErrorMiddleware;
use Nimo\MiddlewareStack;
use Nimo\NimoUtility;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareStackTest extends NimoTestCase
{
    public function testAppend()
    {
        $dummies = $this->makeDummies();

        $stack = new MiddlewareStack();
        $stack->append($dummies['middleware1']);
        $stack->append($dummies['middleware0']);
        $stack->append($dummies['middleware2']);

        $returnValue = $stack($dummies['reqDummy'], $dummies['resDummy'], $dummies['next']);

        $this->assertSame($dummies['resDummy4'], $returnValue);
    }

    public function testPrepend()
    {
        $dummies = $this->makeDummies();

        $stack = new MiddlewareStack();
        $stack->prepend($dummies['middleware2']);
        $stack->prepend($dummies['middleware0']);
        $stack->prepend($dummies['middleware1']);

        $returnValue = $stack($dummies['reqDummy'], $dummies['resDummy'], $dummies['next']);

        $this->assertSame($dummies['resDummy4'], $returnValue);
    }

    public function testErrorHandler()
    {
        $skippedMiddlewareProphecy = $this->prophesize(AbstractMiddleware::class);
        $skippedMiddlewareProphecy->__call('main', [])->shouldNotBeCalled();

        $request = $this->prophesizeServerRequest()->reveal();
        $request2 = $this->prophesizeServerRequest()->reveal();
        $request3 = $this->prophesizeServerRequest()->reveal();
        $response = $this->prophesizeResponse()->reveal();
        $response2 = $this->prophesizeResponse()->reveal();
        $response3 = $this->prophesizeResponse()->reveal();
        $theError = new \stdClass();

        $stack = new MiddlewareStack();
        $errorTriggerMiddleware = function (
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) use (
            $request,
            $request2,
            $response,
            $response2,
            $theError
        ) {
            $this->assertSame($request, $req);
            $this->assertSame($response, $res);

            return $next($request2, $response2, $theError);
        };

        $errorHandle = function (
            $error,
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) use (
            $request2,
            $request3,
            $response2,
            $response3,
            $theError
        ) {
            $this->assertSame($theError, $error);
            $this->assertSame($request2, $req);
            $this->assertSame($response2, $res);

            return $next($request3, $response3);
        };

        $errorHandleMiddlewareProphecy = $this->prophesize(IErrorMiddleware::class);
        $errorHandleMiddlewareProphecy
            ->__call('__invoke', [
                Argument::any(),
                Argument::type(ServerRequestInterface::class),
                Argument::type(ResponseInterface::class),
                Argument::type('callable')
            ])
            ->will(function ($args) use ($errorHandle) {
                list($err, $req, $res, $next) = $args;

                return $errorHandle($err, $req, $res, $next);
            })
            ->shouldBeCalled();

        $skippedErrorMiddlewareProphecy = $this->prophesize(IErrorMiddleware::class);
        $skippedErrorMiddlewareProphecy
            ->__call('__invoke', [
                Argument::any(),
                Argument::type(ServerRequestInterface::class),
                Argument::type(ResponseInterface::class),
                Argument::type('callable')
            ])
            ->shouldNotBeCalled();


        $middleware = $stack
            ->append($skippedErrorMiddlewareProphecy->reveal())
            ->append($errorTriggerMiddleware)
            ->append($skippedMiddlewareProphecy->reveal())
            ->append($errorHandleMiddlewareProphecy->reveal());

        $returnValue = call_user_func(
            $middleware,
            $request,
            $response,
            [NimoUtility::class, 'noopNext']
        );

        $this->assertSame($response3, $returnValue);
    }

    protected function makeDummies()
    {

        $reqProphecy = $this->prophesizeServerRequest();
        $resProphecy = $this->prophesizeResponse();

        $reqDummy = $reqProphecy->reveal();
        $reqDummy2 = $reqProphecy->reveal();
        $reqDummy3 = $reqProphecy->reveal();
        $resDummy = $resProphecy->reveal();
        $resDummy2 = $resProphecy->reveal();
        $resDummy3 = $resProphecy->reveal();
        $resDummy4 = $resProphecy->reveal();

        $middleware0 = function (
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) {
            return $next();
        };

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
