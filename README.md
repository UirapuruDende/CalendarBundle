# CalendarBundle

[![Build Status](https://travis-ci.org/UirapuruDende/CalendarBundle.svg?branch=master)](https://travis-ci.org/UirapuruDende/CalendarBundle)

creating database in mysql:

    mysql -u user -ppassword -e "CREATE DATABASE `calendar_bundle_test`;"
    
creating schema:

    cd calendar-bundle/
    php ./Tests/app/console.php doctrine:schema:create
    
loading test fixtures:

    cd calendar-bundle/
    php ./Tests/app/console.php doctrine:fixtures:load --fixtures="Tests/DataFixtures/Standard"
    
resetting db:

    cd calendar-bundle/
    ./reset-db.sh
    
running localhost server:

    cd calendar-bundle/
    php -S localhost:8080 -t Tests/app/

visiting page of calendar (example):
    
    http://localhost:8080/web.php/api/calendar