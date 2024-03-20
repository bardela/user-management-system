<?php

require_once (__DIR__ . '/database/DatabaseConnection.php');
require_once (__DIR__ . '/database/MysqlConnection.php');
require_once (__DIR__ . '/model/User.php');
require_once (__DIR__ . '/middleware/Middleware.php');
require_once (__DIR__ . '/middleware/AuthMiddleware.php');
require_once (__DIR__ . '/controller/Controller.php');
require_once (__DIR__ . '/controller/UserController.php');
require_once (__DIR__ . '/Request.php');

$config = require_once __DIR__ . '/config/config.php';
$arguments = getopt("", ["action:", "auth:", "id:", "name:", "role:", "page:"]);

$database = new MysqlConnection($config['host'], $config['database'], $config['username'], $config['password'], $config['port']);
$user = new User($database);
$middleware = new AuthMiddleware($user);
$controller = new UserController($user);
$request = new Request($middleware, $controller);
if (!$request->validParameters($arguments)) {
  displayErrorParameters();
  return;
}
$request->do($arguments);

function displayErrorParameters(): void
{
  echo "User Management System Failed. Not enough arguments or wrong arguments\n";
  echo "Usage: php run --action ACTION --auth AUTHENTICATED_AS [OPTIONS]\n";
  echo "Actions: list | create | edit | delete\n";
  echo "Authenticated as: admin | user  | USER ID\n";
  echo "Options: \n";
  echo "  Role: admin | user\n";
  echo "Parameters for Creating a user: --name NAME --role ROLE\n";
  echo "  i.e.: php run --action create --auth admin --name \"John Doe\" --role admin\n";
  echo "Parameters for Editing  a user: --id ID --name NAME --role ROL\n";
  echo "  i.e.: php run --action edit --auth user --id 1 --name \"Peter Doe\" --role user\n";
  echo "Parameters for Deleting a user: --id ID\n";
  echo "  i.e.: php run --action delete --auth 1  --id 1\n";
}