# Snce exercise (Products management)
This web application give to the users the possibility to handle their product from an intuitive UI.
## Features
- Add product
- Edit product
- Delete product
- List view with search filter on products tags 
## Requirements
- php 7.1
- composer
- mysql
## Configuration
1) Download project
2) Enter into project's root and run:
```shell
composer install
```
3) Copy .env.dist and rename it .env
4) In .env file edit (following line): db_user and db_password with the your parameters and change db_name in snce_exercise.
```shell
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```
### Database set up
#### With doctrine
From project's root
- Create Schema
```shell
php bin/console doctrine:database:create
```
- Create entities
```shell
php bin/console doctrine:schema:update --force
```
#### Importing .sql file
>Database will be popolated with 3 example products.
From project root folder run:
```shell
mysql -u [db_username] -p[password] < public/mysql/snce_exercise.sql
```
## Run application
Go to project's root and run:
```shell
php bin/console server:start
```
> To stop the server
```shell
php bin/console server:stop
```

