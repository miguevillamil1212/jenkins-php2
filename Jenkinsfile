pipeline {
  agent any
  environment {
    APP_NAME    = 'jenkins-php-web'
    HOST_PORT   = '8081'
    IMAGE_TAG   = "jenkins-php:${env.BUILD_NUMBER}"
    DOCKER_HOST = 'tcp://host.docker.internal:2375' // clave en Windows
  }
  options { timestamps(); ansiColor('xterm') }

  stages {
    stage('Checkout') {
      steps {
        git branch: 'main', url: 'https://github.com/TU_USUARIO/jenkins-php.git'
      }
    }

    stage('Diagnóstico 2375') {
      agent { docker { image 'curlimages/curl:8.10.1' } }
      steps {
        sh 'curl -fsS http://host.docker.internal:2375/_ping'
      }
    }

    stage('Build image') {
      agent { docker { image 'docker:25.0-cli' } }
      steps {
        sh 'docker version'
        sh 'docker build -t ${IMAGE_TAG} .'
      }
    }

    stage('Deploy container') {
      agent { docker { image 'docker:25.0-cli' } }
      steps {
        sh '''
          docker ps -aq --filter "name=^/${APP_NAME}$" | xargs -r -I {} docker rm -f {}
          docker run -d --name ${APP_NAME} -p ${HOST_PORT}:80 ${IMAGE_TAG}
          sleep 2
        '''
      }
    }

    stage('Smoke test') {
      agent { docker { image 'curlimages/curl:8.10.1' } }
      steps {
        sh 'curl -fsS http://host.docker.internal:${HOST_PORT} >/dev/null'
      }
    }
  }

  post {
    success { echo "Listo: http://host.docker.internal:${HOST_PORT}" }
  }
}
