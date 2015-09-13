<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Nimo\Bundled\FixedResponseMiddleware;
use Nimo\NimoUtility;

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
}
