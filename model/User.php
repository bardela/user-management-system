<?php

class User
{
  private DatabaseConnection $databaseConnection;
  private string $table = 'users';

  public function __construct($databaseConnection) {
    $this->databaseConnection = $databaseConnection;
  }

  public function create(array $userData) {
    $this->databaseConnection->create($this->table, $userData);
  }

  public function delete(int $id) {
    $this->databaseConnection->delete($this->table, $id);
  }

  public function update(int $id, array $userData) {
    $this->databaseConnection->update($this->table, $id, $userData);
  }

  public function findById(int $id): array | false {
    return $this->databaseConnection->findById($this->table, $id);
  }

  public function find(int $offset, int $limit): array {
    return $this->databaseConnection->find($this->table, $offset, $limit);
  }

  public function count(): int {
    return $this->databaseConnection->count($this->table);
  }
}