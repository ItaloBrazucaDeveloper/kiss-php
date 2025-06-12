<?php
namespace KissPhp\Protocols\Http;

use KissPhp\Services\Session;
use KissPhp\Core\Routing\Route;

class Request {
  private Url $url;
  private Header $header;
  private Body $body;
  
  public readonly Session $session;

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

  public function getUrl(): string {
    return $this->url->path;
  }

  public function getMethod(): string {
    return $this->url->method;
  }

  public function getRouteParam(?string $key = null): ?string {
    return $this->url->params->get($key);
  }

  public function getAllRouteParams(): Params {
    return $this->url->params;
  }

  public function getQueryString(?string $key = null): ?string {
    return $this->url->queryStrings->get($key);
  }

  public function getAllQueryStrings(): QueryStrings {
    return $this->url->queryStrings;
  }

  public function setheader(string $key, string $value): void {
    $this->header->set($key, $value);
  }

  public function getHeader(?string $key = null): ?string {
    return $this->header->get($key);
  }

  public function getAllHeaders(): Header {
    return $this->header;
  }

  public function getAllBody(?string $key = null): string {
    return $this->body->get($key);
  }

  public function getBody(): Body {
    return $this->body;
  }
}