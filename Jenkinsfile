node('node') {


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

            mail body: "project build error is here: ${env.BUILD_URL}" ,
            from: 'xxxx@yyyy.com',
            replyTo: 'yyyy@yyyy.com',
            subject: 'project build failed',
            to: 'uirapuruadg@gmail.com'

        throw err
    }

}
