<?php namespace Nimo;

use Exception;

class MiddlewareErrorException extends \Exception
{
    /**
     * @var string
     */
    protected $error;

    public static function wrap($error)
    {
        if ($error instanceof static) {
            return $error;
        }

        return new static($error);
    }

    public function __construct($error, $code = 0, Exception $previous = null)
    {
        parent::__construct('middleware error unhandled', $code, $previous);

        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


}
