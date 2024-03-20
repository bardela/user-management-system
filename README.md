# Users Management System

## Set up

Set up the database before testing
```
docker compose up
```

The credentials can be changed in the file `.env`
```
HOST=127.0.0.1
DATABASE=db
USERNAME=admin
PASSWORD=pass
PORT=3306
```


## Usages:

### Listing users
Syntax
```
php run --action list -auth [admin|user|ID] [--page PAGE]
```
Example list users authenticated as a fake user in the page 0:
```
php run.php --action list --auth user --page 0
```

### Create user
Syntax
```
php run.php --action create --auth [admin|user|ID] --name NAME --role [admin|user]
```
Example create user John Doe with role user
```
php run.php --action create --auth admin --name "John Doe" --role user
```

### Edit user
Syntax
```
php run.php --action edit --auth [admin|user|ID] --id 1 --name NAME --role [admin|user]
```
Example edit user previously created to John Edited with role admin
```
php run.php --action edit --auth admin --id 1 --name "John Edited" --role admin
```


### Delete user
Syntax
```
php run.php --action delete --auth [admin|user|ID] --id 1
```
Example edit user previously created to John Edited with role admin
```
php run.php --action delete --auth admin --id 1
```


# Code Structure
- [run.php](run.php) is the main component that loads all the dependencies and classes

## Dependency Injection
As part of `run.php`, I have use [DI](https://github.com/bardela/user-management-system/blob/main/run.php#L15-L19) for the following classes: 
- [MysqlConnection](database/MysqlConnection.php) -> [DatabaseConnection](database/DatabaseConnection.php) 
- [AuthMiddleware](middleware/AuthMiddleware.php) -> [Middleware](middleware/Middleware.php)

They are both use in the initialization of [Request](https://github.com/bardela/user-management-system/blob/main/Request.php#L15-L19) which has all the actual logic for performing the checks and desired operations. 

## Inheritance
- [UserController](controller/UserController.php) -> [Controller](controller/Controller.php)

## Data Validation
- [AuthMiddleware::handle](https://github.com/bardela/user-management-system/blob/main/middleware/AuthMiddleware.php#L11-L18) validates the authorization/authentication depending on the action(list doesn't require authorization, but authentication)
- [UserController](controller/UserController.php) and [Controller](controller/Controller.php) validate the actual input data

## Sanitization 
- Use [PDO](https://github.com/bardela/user-management-system/blob/main/database/MysqlConnection.php#L16) queries to prevent SQL Injection by preparing [queries](https://github.com/bardela/user-management-system/blob/main/database/MysqlConnection.php#L28-L29)
- In addition, [UserController::extractRole](https://github.com/bardela/user-management-system/blob/main/controller/UserController.php#L66-L72) and [Controller::extractString](https://github.com/bardela/user-management-system/blob/main/controller/Controller.php#L6-L13) provide an extra level of sanitization.

## Separation of concerns
- I made [User](model/User) loosely coupled from the SQL Operations and validation of data. 
It requires a DB connection as specified in specs and it actually works as a repository for User data.
- The `Controllers` classes take care of parsing the data and invoke the respective operation
- The `Middlewares` handle the authentication and authorization only 
