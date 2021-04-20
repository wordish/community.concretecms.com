import gql from "graphql-tag";

export const M_ENVIRONMENT_DEPLOY = gql`
    mutation($branch: String!, $projectId: Int!) {
        deployHostingProject(input:{
            project:$projectId
            branch:$branch
        }) {
            project: hostingProject {
                lagoonProject {
                    environments
                }
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
    mutation($environment: String!, $projectId: Int!) {
        restoreHostingProject(input:{
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
