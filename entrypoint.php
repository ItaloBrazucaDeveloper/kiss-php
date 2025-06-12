<?php

use KissPhp\Services\Container;
use KissPhp\Core\DED\BoundinaryError;
use KissPhp\Core\Routing\DispatchRouter;
use KissPhp\Support\{ Env, SessionInitializer };

BoundinaryError::wrap(function() {
  BoundinaryError::register();
  Env::load(__DIR__ . '/../../');

  include __DIR__ . '/../../app/settings.php';
  SessionInitializer::init();

  $uri = $_SERVER['REQUEST_URI'] ?? '';
  $uriParsed = parse_url($uri, PHP_URL_PATH) ?? '';

  $endpoint = $uriParsed === '/' ? '' : $uriParsed;
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

  $container = Container::getInstance();
  $dispatcher = $container->get(DispatchRouter::class);
  $dispatcher->dispatch($method, $endpoint);
});
