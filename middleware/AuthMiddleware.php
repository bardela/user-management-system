<?php

class AuthMiddleware implements Middleware {
  protected User $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function handle(string $action, string $auth): void
  {
    if ($action == 'list') {
      $this->requiresUser($auth);
      return;
    }
    $this->requiresAdmin($auth);
  }

  function requiresAdmin($id): void
  {
    if (!$this->isAuthUser($id, true)) {
      throw new Exception("Error: not enough permissions. should be an admin");
    }
  }

  function requiresUser($id): void
  {
    if (!$this->isAuthUser($id)) {
      throw new Exception("Error: user invalid");
    }
  }

  function isAuthUser($id, $onlyAdmin = false): bool
  {
    if (is_numeric($id)) {
      $auth = $this->user->findById($id);
      return $onlyAdmin
          ? is_array($auth) && $auth['role'] == 'admin'
          : is_array($auth);
    }

    return $onlyAdmin
        ? $id == 'admin'
        : in_array($id, ['admin', 'user']);
  }
}