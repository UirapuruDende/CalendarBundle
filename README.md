# CalendarBundle

creating database:

    mysql -u user -ppassword -e "CREATE DATABASE `calendar_bundle_test`;"
    
creating schema:

    cd calendar-bundle/
    php ./Tests/app/console.php doctrine:schema:create
    
loading test fixtures:

    cd calendar-bundle/
    php ./Tests/app/console.php doctrine:fixtures:load --fixtures="Tests/DataFixtures/Standard"
    
