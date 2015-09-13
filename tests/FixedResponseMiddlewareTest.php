<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Nimo\Bundled\FixedResponseMiddleware;
use Nimo\NimoUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FixedResponseMiddlewareTest extends NimoTestCase
{
    public function testFixedResponseMiddleware()
    {
        $answerRes = $this->prophesizeResponse()->reveal();
        /** @noinspection PhpParamsInspection */
        $middleware = new FixedResponseMiddleware($answerRes);

        $returnValue = call_user_func(
            $middleware,
            $this->prophesizeServerRequest()->reveal(),
            $this->prophesizeResponse()->reveal(),
            [NimoUtility::class, 'noopNext']
        );

        $this->assertSame($answerRes, $returnValue);
    }

    public function testAppend()
    {
        $response = $this->prophesizeResponse()->reveal();
        $response1 = $this->prophesizeResponse()->reveal();
        $response2 = $this->prophesizeResponse()->reveal();
        $response3 = $this->prophesizeResponse()->reveal();
        $request = $this->prophesizeServerRequest()->reveal();
        $request1 = $this->prophesizeServerRequest()->reveal();

        /** @noinspection PhpParamsInspection */
        $middleware = new FixedResponseMiddleware($response1);
        $middleware1 = function (
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) use (
            $request,
            $request1,
            $response1,
            $response2
        ) {
            $this->assertSame($request, $req);
            $this->assertSame($response1, $res);

            return $next($request1, $response2);
        };

        $next = function (
            ServerRequestInterface $req,
            ResponseInterface $res
        ) use (
            $request1,
            $response2,
            $response3
        ) {
            $this->assertSame($request1, $req);
            $this->assertSame($response2, $res);

            return $response3;
        };

        $returnValue = call_user_func(
            $middleware->append($middleware1),
            $request,
            $response,
            $next
        );

        $this->assertSame($response3, $returnValue);
    }
}
