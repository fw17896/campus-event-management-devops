pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
    }

    triggers {
        githubPush()
    }

    environment {
        COMPOSE_FILE = '/opt/campus-event-management-pipeline/docker-compose.pipeline.yml'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Validate Files') {
            steps {
                sh '''
                    test -f index.php
                    test -f config.php
                    test -f database/init.sql
                    echo "Required application files found."
                '''
            }
        }

        stage('Deploy Application') {
            steps {
                withCredentials([
                    string(
                        credentialsId: 'pipeline-db-password',
                        variable: 'PIPELINE_DB_PASSWORD'
                    ),
                    string(
                        credentialsId: 'pipeline-db-root-password',
                        variable: 'PIPELINE_DB_ROOT_PASSWORD'
                    )
                ]) {
                    sh '''
                        set +x
                        export PIPELINE_SOURCE_DIR="$WORKSPACE"

                        docker compose \
                            -p campus-pipeline \
                            -f "$COMPOSE_FILE" \
                            config -q

                        docker compose \
                            -p campus-pipeline \
                            -f "$COMPOSE_FILE" \
                            up -d --wait

                        curl --fail --retry 10 --retry-delay 3 \
                            http://127.0.0.1:8083/
                    '''
                }
            }
        }
    }

    post {
        success {
            echo 'Part II deployment completed successfully.'
        }

        failure {
            echo 'Pipeline failed. Check the Jenkins console output.'
        }

        always {
            echo "Build result: ${currentBuild.currentResult}"
            echo "Build URL: ${env.BUILD_URL}"
        }
    }
}
