import gql from "graphql-tag";

export const F_DEPLOY_FULL = gql`
fragment DeployFields on Deploy {
    environmentName
    deployName
    dateCreated
    dateUpdated
    fulfillmentStatus
    dateFulfilled
    dateStarted
    id
    _id
}
`

export const Q_DEPLOY_FULL = gql`
    ${F_DEPLOY_FULL}
    query($deployId: ID!) {
        deploy(id:$deployId) {
            ...DeployFields
        }
    }
`

export const Q_DEPLOYS_BY_PROJECT = gql`
    ${F_DEPLOY_FULL}
    query deploysByProject($projectId: String!, $environment: String) {
        deploys(project: $projectId, environmentName: $environment, order:{dateCreated:"desc"}) {
            pageInfo {
                startCursor
                endCursor
                hasNextPage
                hasPreviousPage
            }
            totalCount
            edges {
                node {
                    ...DeployFields
                }
            }
        }
    }
`
export const M_DEPLOY_CREATE = gql`
    ${F_DEPLOY_FULL}
    mutation createDeploy($projectId: String!, $environment: String!) {
        createDeploy(input: {
            project: $projectId,
            environmentName: $environment,
        }) {
            deploy {
                ...DeployFields
            }
        }
    }
    
`
