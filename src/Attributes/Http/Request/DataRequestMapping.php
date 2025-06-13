<?php
namespace KissPhp\Attributes\Http\Request;

use KissPhp\Attributes\Data\DataMapping;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
abstract class DataRequestMapping extends DataMapping {
  abstract public function getRequestAction(): string;
}