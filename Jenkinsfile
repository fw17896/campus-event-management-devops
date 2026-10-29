```groovy
post {
    success {
        emailext(
            subject: "SUCCESS: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
            body: """
Hello,

The Jenkins pipeline completed successfully.

Job: ${env.JOB_NAME}
Build Number: ${env.BUILD_NUMBER}
Build Result: SUCCESS

View Build: ${env.BUILD_URL}

Regards,
Jenkins CI/CD
""",
            to: 'fw7896@gmail.com'
        )
    }

    failure {
        emailext(
            subject: "FAILURE: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
            body: """
Hello,

The Jenkins pipeline has failed.

Job: ${env.JOB_NAME}
Build Number: ${env.BUILD_NUMBER}
Build Result: FAILURE

Please check the console output:
${env.BUILD_URL}console

Regards,
Jenkins CI/CD
""",
            to: 'fw7896@gmail.com'
        )
    }

    always {
        echo "Build result: ${currentBuild.currentResult}"
        echo "Build URL: ${env.BUILD_URL}"
    }
}
```
