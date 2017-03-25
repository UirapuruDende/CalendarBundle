currentBuild.result = "SUCCESS"

try {

   stage 'Checkout'

        checkout scm

   stage 'Prepare'

        sh 'php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"'
        sh 'php composer-setup.php'
        sh 'php -r "unlink(\'composer-setup.php\');"'
        sh 'php composer.phar install --no-interaction --no-ansi --no-progress --dev'

    }

catch (err) {

    currentBuild.result = "FAILURE"

    throw err
}
