#!/bin/bash

php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install

./console.sh assets:install Tests/app --symlink
php ./Tests/app/console.php cache:clear --env=test
php ./Tests/app/console.php doctrine:schema:drop --force
php ./Tests/app/console.php doctrine:schema:create
php ./Tests/app/console.php doctrine:fixtures:load -n -vvv

php -S localhost:8080 -t Tests/app/