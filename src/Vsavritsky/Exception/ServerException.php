<?php

namespace Vsavritsky\Exception;

use Vsavritsky\Exception\BaseException;

/**
 * Class ServerException
 */
class ServerException extends BaseException
{
   function __construct(string $msg, int $code, Throwable $previous = null)
   {
      parent::__construct($msg, $code, $previous);
   }

   public function __toString()
   {
      return "[{$this->code}]: $this->message";
   }
}
