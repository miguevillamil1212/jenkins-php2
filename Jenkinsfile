pipeline {
  agent any

  environment {
    APP_NAME  = 'jenkins-php-web'
    HOST_PORT = '8081'
    IMG_RUN   = 'php:8.2-apache'
    DOCKER_HOST = 'tcp://host.docker.internal:2375'   // << CLAVE EN WINDOWS
  }

  stages {
    stage('Clonar codigo') {
      steps {
        git branch: 'main', url: 'https://github.com/miguevillamil1212/jenkins-php.git'
      }
    }

    stage('Verificar archivos') {
      steps {
        sh '''
          echo "=== ARCHIVOS EN EL REPO ==="
          ls -la
          [ -f index.php ] && head -n 80 index.php || echo "No se encontro index.php"
          [ -f index.html ] && head -n 80 index.html || true
        '''
      }
    }

    stage('Deploy container') {
      agent {
        docker {
          image 'docker:25.0-cli'     // trae docker CLI
          args ''                      // NO montamos /var/run/docker.sock
        }
      }
      steps {
        sh '''
          echo "Probando conexion con Docker (por TCP)"
          docker version

          echo "Bajando contenedor previo si existe"
          docker ps -aq --filter "name=^/${APP_NAME}$" | xargs -r -I {} docker rm -f {}

          echo "Lanzando contenedor nuevo"
          docker run -d --name ${APP_NAME} -p ${HOST_PORT}:80 -v "$PWD":/var/www/html:ro ${IMG_RUN}

          echo "Esperando 2s y probando..."
          sleep 2
        '''
      }
    }

    stage('Smoke test') {
      steps {
        // usa curl en contenedor efimero
        sh '''
          docker run --rm --network host curlimages/curl:8.10.1 -fsS http://host.docker.internal:${HOST_PORT} >/dev/null
          echo "OK: responde en http://host.docker.internal:${HOST_PORT}"
        '''
      }
    }
  }

  post {
    always {
      echo "Contenedores en el host:"
      sh 'docker ps --format "table {{.Names}}\\t{{.Image}}\\t{{.Ports}}" || true'
    }
  }
}
