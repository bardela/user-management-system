<?php

class MysqlConnection implements DatabaseConnection
{
  private PDO $connection;
  const SEPARATOR = ",";

  function __construct(string $host, string $database, string $username, string $password, string $port)
  {
    $this->connect($host, $database, $username, $password, $port);
  }

  public function connect(string $host, string $database, string $username, string $password, string $port)
  {
    try {
      $this->connection = new PDO("mysql:host=$host;dbname=$database;port=$port;charset=utf8", $username, $password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  /**
   * @throws Exception
   */
  private function query(string $sql, array $params): PDOStatement
  {
    $stmt = $this->connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $result = $stmt->execute($params);
    if (!$result) {
      throw new Exception("Query failed: ", print_r($stmt->errorInfo()));
    }
    return $stmt;
  }

  public function findById(string $table, int $id)
  {
    $stmt = $this->query("SELECT * FROM $table WHERE id=:id ", ['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function count(string $table): int
  {
    $stmt = $this->query("SELECT count(*) FROM $table", []);
    return $stmt->fetchColumn();
  }

  public function find(string $table, int $offset, int $limit)
  {
    $stmt = $this->connection->prepare("SELECT * FROM  $table ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    $result = $stmt->execute();
    if (!$result) {
      throw new Exception("Query failed: ", print_r($stmt->errorInfo()));
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create(string $table, array $data): int
  {
    $fields = implode(self::SEPARATOR, array_keys($data));
    $values = $this->buildPlaceholderValues($data);
    $stmt = $this->query("INSERT INTO $table ($fields) VALUES ($values)", $data);
    return $stmt->fetchColumn();
  }

  public function update(string $table, int $id, array $data)
  {
    $fields = $this->buildPlaceholderValues($data, true);
    $this->query("UPDATE $table SET $fields WHERE id=:id", [
        ...$data,
        'id' => $id
    ]);
  }

  public function delete(string $table, int $id)
  {
    $this->query("DELETE FROM $table WHERE id=:id", ['id' => $id]);
  }

  private function buildPlaceholderValues(array $columnsToValues, $forUpdate = false): string
  {
    $columnsPrepared = array_map(function($value) use ($forUpdate) {
      $prefix = $forUpdate ? $value.'=' : '';
      return $prefix. ':' . $value;
    }, array_keys($columnsToValues));
    return implode(self::SEPARATOR, $columnsPrepared);
  }
}