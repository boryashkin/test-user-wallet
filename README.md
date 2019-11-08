# LAMP docker-compose app template

* Nginx
* PHP-FPM
* cli PHP
* MySQL

### Steps

* create .env by copying .env.dist and replacing some values
    * `cp .env.dist .env`
* add some .sql's to /data/init/mysql/ to make mysql initializations while it starting
* `docker-compose up` to warm a setup up
* test if web-app works: http://localhost:9882/
*if so, restart the project as `docker-compose -d` if needed