<?php

namespace KissPhp\Http;

use KissPhp\Routing\Route;

class Request {
  public private(set) Url $url;
  public private(set) Header $header;
  public private(set) Body $body;
  public private(set) Session $session;

  public function __construct(Route $route) {
    $this->url = new Url(
      $route->path,
      $route->method,
      new Params($route->params)
    );
    $this->header = new Header();
    $this->body = new Body();
    $this->session = new Session();
  }
}