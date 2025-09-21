pipeline {
  agent any

  environment {
    APP_NAME  = 'jenkins-php-web'
    HOST_PORT = '8081'                 // puerto en el host
    IMG_RUN   = 'php:8.2-apache'       // imagen runtime
  }

  options {
    timestamps()
    ansiColor('xterm')
  }

  stages {
    stage('Clonar codigo') {
      steps {
        git branch: 'main', url: 'https://github.com/miguevillamil1212/jenkins-php.git'
        echo 'Codigo clonado'
      }
    }

    stage('Verificar archivos') {
      steps {
        sh '''
          echo "=== ARCHIVOS EN EL REPOSITORIO ==="
          ls -la
          echo "=== CONTENIDO DE index.php ==="
          if [ -f index.php ]; then
            sed -n '1,120p' index.php
          else
            echo "No se encontro index.php (si tienes index.html tambien sirve)"
            [ -f index.html ] && sed -n '1,120p' index.html || true
          fi
        '''
        echo 'Archivos verificados'
      }
    }

    // Despliegue usando un agente con Docker CLI y el socket del host
    stage('Deploy container') {
      agent {
        docker {
          image 'docker:25.0-cli'                          // imagen con docker CLI
          args '-v /var/run/docker.sock:/var/run/docker.sock'  // monta socket host
        }
      }
      steps {
        sh '''
          echo "Eliminando contenedor previo (si existe)"
          docker ps -aq --filter "name=^/${APP_NAME}$" | xargs -r -I {} docker rm -f {}

          echo "Levantando contenedor nuevo"
          # Montamos el workspace como /var/www/html (solo lectura)
          docker run -d --name ${APP_NAME} \
            -p ${HOST_PORT}:80 \
            -v "$PWD":/var/www/html:ro \
            ${IMG_RUN}

          echo "Esperando 2s..."
          sleep 2
        '''
      }
    }

    stage('Smoke test') {
      steps {
        // Usamos contenedor de curl para no depender de curl en Jenkins
        sh '''
          echo "Probando http://localhost:${HOST_PORT}"
          docker run --rm --network host curlimages/curl:8.10.1 -fsS http://localhost:${HOST_PORT} >/dev/null \
            && echo "OK: responde en localhost:${HOST_PORT}" \
            || (echo "Intentando host.docker.internal:${HOST_PORT}" && \
                docker run --rm --network host curlimages/curl:8.10.1 -fsS http://host.docker.internal:${HOST_PORT} >/dev/null)

          echo "Smoke test OK"
        '''
      }
    }

    stage('Completar') {
      steps {
        echo "PIPELINE COMPLETADO EXITOSAMENTE - Visita: http://<IP_DEL_HOST>:${HOST_PORT}"
      }
    }
  }

  post {
    always {
      echo 'Estado de contenedores (host):'
      script {
        // Mostrar contenedores desde el mismo agente docker cli
        sh 'docker ps --format "table {{.Names}}\\t{{.Image}}\\t{{.Ports}}" || true'
      }
    }
  }
}
