<?php
namespace KissPhp\Protocols\Http;

use KissPhp\Core\Routing\Route;

class Request {
  public private(set) Url $url;
  public private(set) Header $header;
  public private(set) Body $body;

  public function __construct(Route $route) {
    $this->url = new Url(
      $route->path,
      $route->method,
      new Params($route->params)
    );
    $this->header = new Header();
    $this->body = new Body();
  }
}