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
        script {
            def commitEmail = sh(
                script: 'git log -1 --format=%ae',
                returnStdout: true
            ).trim()

            if (!commitEmail || commitEmail == 'unknown') {
                error('Could not determine the commit author email.')
            }

            emailext(
                subject: "SUCCESS: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """Your committed changes were built and deployed successfully.

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
Result: SUCCESS

Build URL: ${env.BUILD_URL}
""",
                to: commitEmail
            )
        }
    }

    failure {
        script {
            def commitEmail = sh(
                script: 'git log -1 --format=%ae',
                returnStdout: true
            ).trim()

            if (!commitEmail || commitEmail == 'unknown') {
                echo 'Could not determine the commit author email; notification skipped.'
            } else {
                emailext(
                    subject: "FAILURE: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                    body: """Your committed changes could not be built or deployed successfully.

Job: ${env.JOB_NAME}
Build: ${env.BUILD_NUMBER}
Result: FAILURE

Console Output: ${env.BUILD_URL}console
""",
                    to: commitEmail
                )
            }
        }
    }

    always {
        echo "Build result: ${currentBuild.currentResult}"
        echo "Build URL: ${env.BUILD_URL}"
    }
}
}

