<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Interop\Http\Server\RequestHandlerInterface;
use Nimo\Handlers\CallableHandler;
use Nimo\Middlewares\CallableMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class NimoTestCase extends TestCase
{
    protected function throwHandler()
    {
        return CallableHandler::wrap(function (ServerRequestInterface $request) {
            throw new \Exception('should throw');
        });
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequestMock()
    {
        return $this->getMockForAbstractClass(ServerRequestInterface::class);
    }

    /**
     * @return ResponseInterface
     */
    protected function getResponseMock()
    {
        return $this->getMockForAbstractClass(ResponseInterface::class);
    }

    protected function assertedHandler(ServerRequestInterface $expectedRequest, ResponseInterface $response)
    {
        return CallableHandler::wrap(function (ServerRequestInterface $request) use ($expectedRequest, $response) {
            self::assertSame($expectedRequest, $request);
            return $response;
        });
    }

    protected function assertedNoopMiddleware(
        ServerRequestInterface $expectedRequest,
        ServerRequestInterface $passedRequest = null
    ) {
        $middleware = function (ServerRequestInterface $request, RequestHandlerInterface $handler)
        use ($expectedRequest, $passedRequest) {
            self::assertSame($expectedRequest, $request);
            return $handler->handle($passedRequest ?: $request);
        };

        return CallableMiddleware::wrap($middleware);
    }

    protected function assertedMiddleware(
        ServerRequestInterface $expectedRequest,
        RequestHandlerInterface $expectedHandler,
        ResponseInterface $response
    ) {
        $middleware = function (ServerRequestInterface $request, RequestHandlerInterface $handler)
        use (
            $expectedRequest,
            $expectedHandler,
            $response
        ) {
            self::assertSame($expectedRequest, $request);
            self::assertSame($expectedHandler, $handler);
            return $response;
        };
        return CallableMiddleware::wrap($middleware);
    }
}
