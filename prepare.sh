#!/bin/bash

php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install

php ./Tests/app/console.php assets:install Tests/app --symlink
php ./Tests/app/console.php cache:clear --env=dev
php ./Tests/app/console.php doctrine:schema:drop --force
php ./Tests/app/console.php doctrine:schema:create
php ./Tests/app/console.php doctrine:fixtures:load -n -vvv --fixtures=./Tests/DataFixtures/ORM
php ./Tests/app/console.php server:start localhost:8080 --docroot=Tests/app/

echo "Open in browser http://localhost:8080/web.php/calendar"