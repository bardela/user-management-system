<?php


class UserController extends Controller
{
  private User $user;

  const PAGE_SIZE = 10;
  const COLUMNS = ["id", "name", "role"];

  public function __construct(User $user) {
    $this->user = $user;
  }

  public function list($params) {
    $page = $this->extractInt($params, "page", false, 0);
    $offset = $page * self::PAGE_SIZE;
    $count = $this->user->count();

    $maxPages = max(0, intdiv($count, self::PAGE_SIZE) + ($count % self::PAGE_SIZE > 0 ? 1 : 0) - 1);
    $currentPage = "Page: " . $page . "/" . ($maxPages);
    if ($page > $maxPages) {
      $this->response([], true, $currentPage);
      return;
    }

    $totalUsers =  $count > 0 ? "Total Users: $count. $currentPage" : "Total Users: $count";
    $userLists = $this->user->find($offset, self::PAGE_SIZE);
    $this->response($userLists, true, $totalUsers);
  }

  public function edit($arguments) {
    $id = $this->extractInt($arguments, "id", true);
    $name = $this->extractString($arguments, "name", false);
    $role = $this->extractRole($arguments);
    if (!$this->user->findById($id)) {
      $this->response("Can not edit! User id $id not found");
      return;
    }
    $this->user->update($id, ['name' => $name, 'role'=> $role]);
    $this->response('Edit Succeed!');
  }

  public function delete($arguments) {
    $id = $this->extractInt($arguments, "id", true);
    if (!$this->user->findById($id)) {
      $this->response("Can not delete! User id $id not found!");
      return;
    }
    $this->user->delete($id);
    $this->response('Delete Succeed!');
  }

  public function create($arguments) {
    $name = $this->extractString($arguments, "name", false);
    $role = $this->extractRole($arguments);
    $this->user->create(['name' => $name, 'role'=> $role]);
    $this->response('Create Succeed!');
  }

  protected function getColumns(): array
  {
    return self::COLUMNS;
  }

  private function extractRole($arguments) {
    $role = $this->extractString($arguments, "role", false);
    if (!$role || !in_array($role, ['admin', 'user'])) {
      throw new Exception('Error please enter a valid role: admin | user');
    }
    return $role;
  }
}