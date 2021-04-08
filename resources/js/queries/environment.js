import gql from "graphql-tag";

export const M_ENVIRONMENT_DEPLOY = gql`
    mutation($branch: String!, $projectId: Int!) {
        deployProject(input:{
            project:$projectId
            branch:$branch
        }) {
            project {
                lagoonProject {
                    environments
                }
            }
        }
    }
`

export const M_ENVIRONMENT_BACKUP = gql`
    mutation($environment: String!, $projectId: Int!) {
        backupProject(input:{
            project:$projectId
            environment:$environment
        }) {
            project {
                lagoonProject {
                    environments
                }
            }
        }
    }
`

export const M_ENVIRONMENT_RESTORE = gql`
    mutation($environment: String!, $projectId: Int!) {
        restoreProject(input:{
            project:$projectId
            environment:$environment
        }) {
            project {
                lagoonProject {
                    environments
                }
            }
        }
    }
`
