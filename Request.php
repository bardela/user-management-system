<?php

class Request
{
  CONST HEADER_PARAMS = ['action', 'auth'];
  protected Middleware $middleware;
  protected Controller $controller;

  public function __construct(Middleware $middleware, Controller $controller)
  {
    $this->middleware = $middleware;
    $this->controller = $controller;
  }

  public function do($arguments): void
  {
    $this->middleware->handle($arguments['action'], $arguments['auth']);
    call_user_func(array($this->controller, $arguments['action']), $this->extractParams($arguments));
  }

  function extractParams($arguments, $fromHeader = false): array
  {
    return array_filter($arguments, function($param) use ($fromHeader) {
      return in_array($param, self::HEADER_PARAMS) === $fromHeader;
    }, ARRAY_FILTER_USE_KEY );
  }

  function validParameters($arguments): bool
  {
    if (!isset($arguments['action']) || !isset($arguments['auth'])) {
      return false;
    }
    return match ($arguments["action"]) {
      'create' => isset($arguments['name']) && isset($arguments['role']),
      'modify' => isset($arguments['name']) && isset($arguments['role']) && isset($arguments['id']),
      'delete' => isset($arguments['id']),
      'list' => true,
      default => false
    };
  }
}