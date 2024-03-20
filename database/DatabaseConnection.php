<?php

interface DatabaseConnection
{
  public function connect(string $host, string $database, string $username, string $password, string $port);

  public function count(string $table): int;
  public function findById(string $table, int $id);
  public function find(string $table, int $offset, int $limit);
  public function create(string $table, array $data): int;
  public function delete(string $table, int $id);
  public function update(string $table, int $id, array $data);
}

