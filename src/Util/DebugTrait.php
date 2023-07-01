<?php

namespace Util;

trait DebugTrait
{
  protected function dd(...$args): void
  {
    $this->dump(...$args) && die;
  }

  protected function dump(...$args): void
  {
    $this->header();

    array_map(
      fn ($mixed) => var_dump($mixed),
      ...$args
    );
  }

  protected function header(): void
  {
    $header = 'Content-Type: text/plain';

    if (!$this->headerPrepared($header)) {
      header($header);
      $this->timestamp = -microtime(true);
    }
  }

  protected function headerPrepared(string $header): bool
  {
    $header = trim($header, ': ');

    foreach (headers_list() as $listedHeader) {
      if (stripos($listedHeader, $header) !== false) {
        return true;
      }
    }

    return false;
  }

  protected function pd(...$args): void
  {
    $this->printVars(func_get_args()) && die;
  }

  protected function printVars(...$args): void
  {
    $this->header();

    array_map(
      fn ($mixed) => print_r($mixed),
      func_get_args()
    );
  }
}
