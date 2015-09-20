<?php namespace Nimo\Bundled;

use Nimo\NimoUtility;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class PathSwitcher
{
    protected $notFoundMiddleware;
    protected $paths = [];

    /**
     * @param mixed $notFoundMiddleware the middleware to execute when the path is unknown
     */
    public function __construct($notFoundMiddleware)
    {
        $this->notFoundMiddleware = NimoUtility::wrap($notFoundMiddleware);
    }

    /**
     * @param string $path the path
     * @param mixed $middleware corresponding middleware to execute
     * @return $this
     */
    public function addPath($path, $middleware)
    {
        $path = ltrim($path, '/');
        if (isset($this->paths[$path])) {
            throw new \InvalidArgumentException('path already exist');
        }
        $this->paths[$path] = NimoUtility::wrap($middleware);

        return $this;
    }

    /**
     * @param array $paths [$path => $middleware] pairs
     * @return $this
     */
    public function addPaths($paths)
    {
        foreach ($paths as $path => $middleware) {
            $this->addPath($path, $middleware);
        }

        return $this;
    }

    public function makeMiddleware()
    {
        return new SwitchMiddleware($this);
    }

    /**
     * @param ServerRequestInterface $request
     * @return callable $middleware
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $path = ltrim($request->getUri()->getPath(), '/');
        if (!isset($this->paths[$path])) {
            return $this->notFoundMiddleware;
        } else {
            return $this->paths[$path];
        }
    }
}
