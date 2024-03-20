<?php

$env = parse_ini_file('.env');
return [
  'host' => $env['HOST'] ?: 'localhost',
  'database' => $env['DATABASE'] ?: 'db',
  'username' => $env['USERNAME'] ?: 'admin',
  'password' => $env['PASSWORD'] ?: 'pass',
  'port' => $env['PORT'] ?: '3306',
];
