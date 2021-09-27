import gql from "graphql-tag";

export const F_ENVIRONMENT_FULL = gql`
    fragment EnvironmentFields on Environment {
        environmentName
        fulfillmentStatus
        dateCreated
        environmentType
        dateFulfilled
        services
        route
        routes
        dateStarted
        dateUpdated
        id
        lagoonId
    }
`

export const Q_ENVIRONMENTS_BY_PROJECT = gql`
    ${F_ENVIRONMENT_FULL}
    query environmentList($projectId: String!, $after: String, $before: String, $perPage: Int!, $order: [EnvironmentFilter_order]) {
        environments(after: $after, before: $before, first: $perPage, project: $projectId, order: $order) {
            totalCount
            pageInfo {
                hasNextPage
                hasPreviousPage
                endCursor
                startCursor
            }
            edges {
                node {
                    ...EnvironmentFields
                }
            }
        }
    }
`

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


export const M_ENVIRONMENT_CREATE = gql`
    ${F_ENVIRONMENT_FULL}
    mutation createEnvironment($projectId: String!, $environmentName: String!) {
        createEnvironment(input: {
            project: $projectId
            environmentName: $environmentName
            environmentType: "DEVELOPMENT"
        }) {
            environment {
                ...EnvironmentFields
            }
        }
    }
`
