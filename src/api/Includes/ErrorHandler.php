<?php

class ErrorHandler
{

  /**
   * Handle exceptions by setting HTTP response code and outputting error details.
   *
   * @param Throwable $exception The exception that was thrown.
   *
   * @return void
   */
  public static function handleException(Throwable $exception): void
  {
    http_response_code(500);

    echo json_encode([
      "code" => $exception->getCode(),
      "message" => $exception->getMessage(),
      "file" => $exception->getFile(),
      "line" => $exception->getLine()
    ]);
  }

  /**
   * Handle PHP errors by throwing ErrorException with details.
   *
   * @return bool Always returns false.
   */
  public static function handleError(
    int $errno,
    string $errstr,
    string $errfile,
    int $errline
  ): bool {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }
}
