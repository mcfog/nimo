<?php namespace Nimo\Bundled;

use Nimo\AbstractMiddleware;
use Nimo\NimoUtility;

/**
 * User: mcfog
 * Date: 15/9/4
 */
abstract class AbstractWrapperMiddleware extends AbstractMiddleware
{
    /**
     * @var callable
     */
    protected $innerMiddleware;

    public function __construct($innerMiddleware)
    {
        $this->innerMiddleware = NimoUtility::wrap($innerMiddleware);
    }
}
