pipeline {
  agent any
  environment {
    APP_NAME  = 'jenkins-php-web'
    HOST_PORT = '8081'
  }

  stages {
    stage('Checkout') {
      steps {
        git branch: 'main', url: 'https://github.com/miguevillamil1212/jenkins-php.git'
      }
    }

    stage('Deploy container') {
      steps {
        sh '''
          # parar contenedor previo si existe
          docker ps -aq --filter "name=^/${APP_NAME}$" | xargs -r -I {} docker rm -f {}

          # levantar contenedor nuevo montando el workspace como /var/www/html
          docker run -d --name ${APP_NAME} \
            -p ${HOST_PORT}:80 \
            -v "$PWD":/var/www/html:ro \
            php:8.2-apache

          # verificación simple
          sleep 2
          curl -sSf http://localhost:${HOST_PORT} >/dev/null && echo "OK"
        '''
      }
    }
  }

  post {
    success {
      echo "Abre: http://<IP_DEL_SERVIDOR>:${HOST_PORT}"
      sh 'docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Ports}}"'
    }
  }
}
