pipeline {
  agent any
  environment {
    APP_NAME  = 'jenkins-php-web'
    HOST_PORT = '8081'
    IMAGE_TAG = "jenkins-php:${env.BUILD_NUMBER}"
  }
  options { timestamps() }

  stages {
    stage('Checkout') {
      steps {
        git branch: 'main', url: 'https://github.com/miguevillamil1212/jenkins-php2.git'
      }
    }

    stage('Build image') {
      steps {
        bat 'docker version'
        bat 'docker build -t %IMAGE_TAG% .'
      }
    }

    stage('Deploy container') {
      steps {
        bat 'for /f %A in (\'docker ps -aq --filter "name=^/%APP_NAME%$"\') do docker rm -f %A'
        bat 'docker run -d --name %APP_NAME% -p %HOST_PORT%:80 %IMAGE_TAG%'
        bat 'docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Ports}}"'
      }
    }

    stage('Smoke test') {
      steps {
        bat 'powershell -Command "Invoke-WebRequest http://localhost:%HOST_PORT% -UseBasicParsing | Out-Null"'
      }
    }
  }

  post {
    success { echo "OK → http://localhost:%HOST_PORT%" }
  }
}
