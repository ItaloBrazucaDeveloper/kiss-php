<?php
namespace KissPhp\Enums;

enum FlashMessageType: string {
  case Success = 'success';
  case Error = 'error';
  case Warning = 'warning';
  case Info = 'info';
}
