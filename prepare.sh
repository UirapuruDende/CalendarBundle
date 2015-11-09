#!/bin/bash

php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install

./console.sh assets:install Tests/app --symlink
./reset-db.sh

php -S localhost:8080 -t Tests/app/