<?php
namespace KissPhp\Enums;

enum MessageType: string {
  case Success = 'success';
  case Error = 'error';
  case Warning = 'warning';
  case Info = 'info';
}
