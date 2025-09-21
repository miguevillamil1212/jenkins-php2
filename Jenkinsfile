pipeline {
    agent any
    
    stages {
        stage('Clonar codigo') {
            steps {
                // CORREGIDO: parámetros con ':' y URL con https://
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
                        cat index.php
                    else
                        echo "No se encontro index.php"
                    fi
                '''
                echo 'Archivos verificados'
            }
        }
        
        stage('Simular validacion') {
            steps {
                sh '''
                    echo "=== SIMULACION DE VALIDACION ==="
                    echo "Si PHP estuviera instalado, se validaria la sintaxis"
                    echo "php -l index.php"
                    echo "=== SIMULACION COMPLETADA ==="
                '''
                echo 'Validacion simulada'
            }
        }
        
        stage('Completar') {
            steps {
                echo 'PIPELINE COMPLETADO EXITOSAMENTE'
            }
        }
    }
}
