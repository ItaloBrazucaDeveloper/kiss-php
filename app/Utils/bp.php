<?php

namespace App\Utils;

/**
 * Exibe na tela de forma amigável um valor, ótima ferramenta para debugar
 *
 * @param mixed $value The variable to print.
 */
function bp($value): void {
  echo'<pre>';
  print_r($value);
  echo'</pre>';
  die();
}