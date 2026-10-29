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
                    set -eu
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
                    string(credentialsId: 'pipeline-db-password', variable: 'PIPELINE_DB_PASSWORD'),
                    string(credentialsId: 'pipeline-db-root-password', variable: 'PIPELINE_DB_ROOT_PASSWORD')
                ]) {
                    sh '''
                        set -eu
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

                        curl --fail --silent --show-error \
                            --retry 10 --retry-delay 3 \
                            http://127.0.0.1:8083/ \
                            -o /dev/null

                        echo "Deployment and HTTP health check successful."
                    '''
                }
            }
        }
    }

    post {
        success {
            emailext(
                subject: "SUCCESS: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """The Jenkins pipeline completed successfully.

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
Result: SUCCESS

Build URL: ${env.BUILD_URL}
""",
                to: 'fw7896@gmail.com'
            )
        }

        ```groovy id="v9h2fs"
        failure {
            emailext(
                subject: "FAILURE: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """The Jenkins pipeline failed.

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
Result: FAILURE

Console Output: ${env.BUILD_URL}console
""",
                to: 'fw7896@gmail.com'
            )
        }

        always {
            echo "Build result: ${currentBuild.currentResult}"
            echo "Build URL: ${env.BUILD_URL}"
        }
    }
}
