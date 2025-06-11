<?php
namespace KissPhp\Protocols\Http;

class Url {
  public private(set) string $path;
  public private(set) string $method;
  
  public private(set) Params $params;
  public private(set) QueryStrings $queryStrings;

  public function __construct(
    string $path, string $method, Params $params
  ) {
    $this->path = $path;
    $this->method = $method;
    $this->params = $params;
    $this->queryStrings = new QueryStrings();
  }
}