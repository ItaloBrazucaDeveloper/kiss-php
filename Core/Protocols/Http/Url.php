<?php

namespace Infra\Http;

class Url {
  public private(set) QueryStrings $queryStrings;

  public function __construct(
    public private(set) string $path,
    public private(set) string $method,
    public private(set) Params $params,
  ) {
    $this->path = $path;
    $this->method = $method;
    $this->params = $params;
    $this->queryStrings = new QueryStrings();
  }
}