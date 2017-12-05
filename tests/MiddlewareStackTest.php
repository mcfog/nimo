<?php namespace Nimo\Tests;

use Nimo\MiddlewareStack;

/**
 * User: mcfog
 * Date: 15/9/12
 */
class MiddlewareStackTest extends NimoTestCase
{
    public function testEmptyStack()
    {
        $stack = new MiddlewareStack();
        $answerRes = $this->getResponseMock();
        $request = $this->getRequestMock();

        $handler = $this->assertedHandler($request, $answerRes);
        $res = $stack->process($request, $handler);
        self::assertSame($answerRes, $res);
    }

    public function testAppend()
    {
        $stack = new MiddlewareStack();
        $request = $this->getRequestMock();
        $request2 = $this->getRequestMock();
        $request3 = $this->getRequestMock();
        $response = $this->getResponseMock();
        $middleware1 = $this->assertedNoopMiddleware($request, $request2);
        $middleware2 = $this->assertedNoopMiddleware($request2, $request3);
        $handler = $this->assertedHandler($request3, $response);

        $stack
            ->append($middleware1)
            ->append($middleware2);

        $res = $stack->process($request, $handler);
        self::assertSame($response, $res);
    }

    public function testPrepend()
    {
        $stack = new MiddlewareStack();
        $request = $this->getRequestMock();
        $request2 = $this->getRequestMock();
        $request3 = $this->getRequestMock();
        $response = $this->getResponseMock();
        $middleware1 = $this->assertedNoopMiddleware($request, $request2);
        $middleware2 = $this->assertedNoopMiddleware($request2, $request3);
        $handler = $this->assertedHandler($request3, $response);

        $stack
            ->prepend($middleware2)
            ->prepend($middleware1);

        $res = $stack->process($request, $handler);
        self::assertSame($response, $res);
    }
}
