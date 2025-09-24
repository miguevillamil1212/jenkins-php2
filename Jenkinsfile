pipeline {
  agent any
  environment {
    APP_NAME    = 'jenkins-php-web'
    HOST_PORT   = '8081'
    IMAGE_TAG   = "jenkins-php:${env.BUILD_NUMBER}"
    DOCKER_HOST = 'tcp://host.docker.internal:2375'  // clave en Windows
  }

  options {
    timestamps()  // <-- dejamos solo esto
  }

  stages {
    stage('Checkout') {
      steps {
        git branch: 'main', url: 'https://github.com/miguevillamil1212/jenkins-php2.git'
      }
    }

    stage('Diagnóstico 2375') {
      agent { docker { image 'curlimages/curl:8.10.1' } }
      steps {
        sh 'curl -fsS http://host.docker.internal:2375/_ping || (echo "Daemon Docker no responde en 2375" && exit 1)'
      }
    }

    stage('Build image') {
      agent { docker { image 'docker:25.0-cli' } }
      steps {
        sh '''
          docker version || exit 1
          docker build -t ${IMAGE_TAG} .
          docker images | head -n 10
        '''
      }
    }

    stage('Deploy container') {
      agent { docker { image 'docker:25.0-cli' } }
      steps {
        sh '''
          docker ps -aq --filter "name=^/${APP_NAME}$" | xargs -r -I {} docker rm -f {}
          docker run -d --name ${APP_NAME} -p ${HOST_PORT}:80 ${IMAGE_TAG}
          docker ps --format "table {{.Names}}\\t{{.Image}}\\t{{.Ports}}"
          sleep 2
        '''
      }
    }

    stage('Smoke test') {
      agent { docker { image 'curlimages/curl:8.10.1' } }
      steps {
        sh 'curl -fsS http://host.docker.internal:${HOST_PORT} >/dev/null || (echo "No responde en ${HOST_PORT}" && exit 1)'
      }
    }
  }

  post {
    success { echo "OK → http://host.docker.internal:${HOST_PORT}" }
    always  { echo "Fin del pipeline" }
  }
}
