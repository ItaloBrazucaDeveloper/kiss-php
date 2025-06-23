<?php
namespace KissPhp\Protocols\Http;

use KissPhp\Traits\Redirect;
use KissPhp\Services\Session;
use KissPhp\Core\Routing\Route;
use KissPhp\Core\Types\UploadedFile;
use KissPhp\Core\Protocols\UploadedFilesCollection;

class Request {
  use Redirect;

  private Url $url;
  private Header $header;
  private Body $body;
  public Session $session;
  private UploadedFilesCollection $files;

  public function __construct(Route $route) {
    $this->url = new Url(
      $route->path,
      $route->method,
      new Params($route->params)
    );
    $this->header = new Header();
    $this->body = new Body();
    $this->session = new Session();
    $this->files = new UploadedFilesCollection();
  }

  public function getUrl(): string {
    return $this->url->path;
  }

  public function getMethod(): string {
    return $this->url->method;
  }

  public function getRouteParam(string $key): ?string {
    return $this->url->params->get($key);
  }

  public function getAllRouteParams(): array {
    return $this->url->params->getAll();
  }

  public function getQueryString(?string $key = null): ?string {
    return $this->url->queryStrings->get($key);
  }

  public function getAllQueryStrings(): array {
    return $this->url->queryStrings->getAll();
  }

  public function setheader(string $key, string $value): void {
    $this->header->set($key, $value);
  }

  public function getHeader(string $key): ?string {
    return $this->header->get($key);
  }

  public function getAllHeaders(): array {
    return $this->header->getAll();
  }

  public function getBody(string $key): ?string {
    return $this->body->get($key);
  }

  public function getAllFiles(string $key): array {
    return $this->files->getAll();
  }

  public function getFile(string $key): ?UploadedFile {
    return $this->files->get($key);
  }

  public function getAllBody(): array {
    return $this->body->getAll();
  }
}