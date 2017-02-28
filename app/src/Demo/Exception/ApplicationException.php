<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 19:45
 */

namespace Demo\Exception;

use Phalcon\Http\Response\Exception;

/**
 * Class ResponseException
 * @package Demo\Exception
 */
class ApplicationException extends Exception
{

    /**
     * Default Exception $defaultMessage
     * @var string
     */
    const DEFAULT_MESSAGE = 'Application error';

    /**
     * ResponseException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code=500, \Exception $previous = null)
    {
        if (!$message){
            $message = self::DEFAULT_MESSAGE;
        }
        parent::__construct($message, $code, $previous);
    }
}
