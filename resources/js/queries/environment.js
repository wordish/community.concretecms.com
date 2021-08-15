import gql from "graphql-tag";

export const M_ENVIRONMENT_DEPLOY = gql`
    mutation($branch: String!, $projectId: String!) {
        createDeploy(input: {
            project: $projectId
            environmentName: $branch
        }) {
            deploy {
                id
                deployName
            }
        }
    }
`

export const M_ENVIRONMENT_BACKUP = gql`
    mutation($environment: String!, $projectId: Int!) {
        backupHostingProject(input:{
            project:$projectId
            environment:$environment
        }) {
            project: hostingProject {
                lagoonProject {
                    environments
                }
            }
        }
    }
`

export const M_ENVIRONMENT_RESTORE = gql`
    mutation($environment: String!, $projectId: Int!, $backup: String!) {
        restoreHostingProject(input:{
            project:$projectId
            environment:$environment
            backup:$backup
        }) {
            project: hostingProject {
                lagoonProject {
                    environments
                }
            }
        }
    }
`

export const M_ENVIRONMENT_INSTALL = gql`
    mutation($environment: String!, $projectId: Int!) {
        installHostingProject(input:{
            project:$projectId
            environment:$environment
        }) {
            project: hostingProject {
                lagoonProject {
                    environments
                }
            }
        }
    }
`

