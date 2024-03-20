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
- `run.php` is the main component that loads all the dependencies and classes

## Dependency Injection
As part of `run.php`, I have use DI in the following classes 
- `MysqlConnection` -> `Database` 
- `AuthMiddleware` -> `Middleware`

They are both use in the initialization of `Request` which has all the actual logic for performing the checks and desired operations. 

## Inheritance
- `AuthController` -> `Controller`

## Data Validation
- `AuthMiddleware` validates the authorization/authentication depending on the action(list doesn't require authorization, but authentication)
- `UserController` & `Controller` validate the actual input data

## Sanitization 
- Use `PDO` queries to prevent SQL Injection by preparing queries in `MysqlConnection`.
- In addition, `UserController` & `Controller` provide an extra level of sanitization.

## Separation of concerns
- I made `User` loosely coupled from the SQL Operations and validation of data
- The `Controllers` classes take care of parsing the data and invoke the respective operation
- The `Middlewares` handle the authentication and authorization only 