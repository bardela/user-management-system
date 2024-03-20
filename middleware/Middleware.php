<?php
interface Middleware {
  public function handle(string $action, string $auth): void;
}