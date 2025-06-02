<?php declare(strict_types=1);

require '../vendor/autoload.php';

use KissPhp\Config\Paths;
use KissPhp\Config\Session;
use KissPhp\Core\DED\BoundinaryError;
use KissPhp\Core\Routing\DispatchRouter;
use KissPhp\Services\Container;
use KissPhp\Services\Dotenv as ServicesDotenv;

BoundinaryError::register();

Session::init();
ServicesDotenv::load(Paths::ENV_PATH);

$uri = $_SERVER['REQUEST_URI'] ?? '';
$uriParsed = parse_url($uri, PHP_URL_PATH) ?? '';

$endpoint = $uriParsed === '/' ? '' : $uriParsed;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

BoundinaryError::wrap(function() use ($endpoint, $method) {
  $container = Container::getInstance();
  $dispatcher = $container->get(DispatchRouter::class);
  $dispatcher->dispatch($method, $endpoint);
});