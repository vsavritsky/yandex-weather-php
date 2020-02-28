<?php

namespace Vsavritsky\Exception;

/**
 * BaseException
 */
class BaseException extends \Exception
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
