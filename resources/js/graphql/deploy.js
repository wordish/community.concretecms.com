import gql from "graphql-tag";
import {F_QUERY_SESSION} from "./session";
import {F_QUERY_PROJECT} from "./project";

export const F_DEPLOY_FULL = gql`
    fragment DeployFull on Deploy {
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
`;

export const F_QUERY_DEPLOY = gql`
    ${F_DEPLOY_FULL}

    fragment QueryDeploy on Query {
        deploy(id: $deployId) {
            ...DeployFull
        }
    }
`

export const F_QUERY_DEPLOYS_LIST = gql`
    ${F_DEPLOY_FULL}
    fragment QueryDeploysList on Query {
        deploys(
            after: $deploysAfter
            before: $deploysBefore
            environmentName: $deploysEnvironmentName
            environmentName_list: $deploysEnvironmentNameList
            first: $deploysFirst
            fulfillmentStatus: $deploysFulfillmentStatus
            fulfillmentStatus_list: $deploysFulfillmentStatusList
            last: $deploysLast
            order: $deploysOrder
            project: $deploysProject
            project_list: $deploysProjectList
        ) {
            totalCount
            edges {
                cursor
                node {
                    ...DeployFull
                }
            }
            pageInfo {
                hasNextPage
                hasPreviousPage
                endCursor
                startCursor
            }
        }
    }
`;

export const Q_QUERY_DEPLOYS_LIST_SESSION = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_DEPLOYS_LIST}
    
    query DeploysListSession(
        $deploysAfter: String,
        $deploysBefore: String,
        $deploysEnvironmentName: String,
        $deploysEnvironmentNameList: [String],
        $deploysFirst: Int,
        $deploysFulfillmentStatus: String,
        $deploysFulfillmentStatusList: [String],
        $deploysLast: Int,
        $deploysOrder: [DeployFilter_order],
        $deploysProject: String,
        $deploysProjectList: [String]
    ) {
        ...QuerySession
        ...QueryDeploysList
    }
`
export const Q_QUERY_DEPLOYS_LIST_SESSION_PROJECT = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_DEPLOYS_LIST}
    ${F_QUERY_PROJECT}

    query DeploysListSession(
        $deploysAfter: String,
        $deploysBefore: String,
        $deploysEnvironmentName: String,
        $deploysEnvironmentNameList: [String],
        $deploysFirst: Int,
        $deploysFulfillmentStatus: String,
        $deploysFulfillmentStatusList: [String],
        $deploysLast: Int,
        $deploysOrder: [DeployFilter_order],
        $deploysProject: String!,
        $deploysProjectList: [String],
        $projectId: ID!
    ) {
        ...QuerySession
        ...QueryDeploysList
        ...QueryProject
    }
`