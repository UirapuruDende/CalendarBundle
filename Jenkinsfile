node {
    pipeline {
        agent any

        stages {
            stage('Build') {

                steps {
                    checkout([$class: 'GitSCM'])
                    sh 'php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"'
                    sh 'php composer-setup.php'
                    sh 'php -r "unlink(\'composer-setup.php\');"'
                    sh 'php composer.phar install --no-interaction --no-ansi --no-progress --dev'
                }
            }
            stage('Test') {
                steps {
                    echo 'Testing..'
                }
            }
            stage('Deploy') {
                steps {
                    echo 'Deploying....'
                }
            }
        }
    }
}