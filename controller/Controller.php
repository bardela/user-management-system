<?php
abstract class Controller {

  abstract protected function getColumns();

  protected function extractString($arguments, string $name, bool $required): string
  {
    $value = array_key_exists($name, $arguments) ? preg_replace('/[^a-zA-Z ]/', '', $arguments[$name]) : null;
    if ($required && !$value) {
      throw new Exception('Error please enter a valid name: alphabetic characters and space only');
    }
    return $value;
  }

  protected function extractInt($arguments, $name, $required, $default = null): int
  {
    $int = array_key_exists($name, $arguments) ? filter_var($arguments[$name], FILTER_VALIDATE_INT) : $default;
    if ($required && $int == null) {
      throw new Exception('Error please enter a valid '. $name . ".");
    }
    return $int;
  }

  protected function response($content, $header = false, $extra = null): void
  {
    if (!is_array($content)) {
      echo $content.$extra."\n";
      return;
    }

    if (count($content) == 0) {
      echo "No data found. $extra\n";
      return;
    }
    if ($header) {
      echo implode("\t|\t", $this->getColumns()). "\n";
    }
    foreach ( $content as $line ) {
      echo implode("\t|\t", $line). "\n";
    }
    if ($extra) {
      echo "$extra\n";
    }
  }
}