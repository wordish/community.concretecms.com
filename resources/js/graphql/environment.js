import gql from "graphql-tag";
import {F_QUERY_SESSION} from "./session";
import {F_QUERY_PROJECT} from "./project";

export const F_ENVIRONMENT_FULL = gql`
    fragment EnvironmentFull on Environment {
        dateCreated
        dateFulfilled
        dateStarted
        dateUpdated
        environmentName
        environmentType
        fulfillmentStatus
        id
        lagoonId
        route
        routes
        services
    }
`;

export const F_QUERY_ENVIRONMENT = gql`
    ${F_ENVIRONMENT_FULL}

    fragment QueryEnvironment on Query {
        environment: environment(id: $environmentId) {
            ...EnvironmentFull
        }
    }
`

export const F_QUERY_ENVIRONMENTS_LIST = gql`
    ${F_ENVIRONMENT_FULL}
    fragment QueryEnvironmentsList on Query {
        environments: environments(
            after: $environmentsAfter
            before: $environmentsBefore
            environmentName: $environmentsEnvironmentName
            environmentName_list: $environmentsEnvironmentNameList
            first: $environmentsFirst
            fulfillmentStatus: $environmentsFulfillmentStatus
            fulfillmentStatus_list: $environmentsFulfillmentStatusList
            last: $environmentsLast
            order: $environmentsOrder
            project: $environmentsProject
            project_list: $environmentsProjectList
        ) {
            totalCount
            edges {
                cursor
                node {
                    ...EnvironmentFull
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

export const Q_QUERY_ENVIRONMENTS_LIST_SESSION = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_ENVIRONMENTS_LIST}
    
    query EnvironmentsListSession(
        $environmentsType: String,
        $environmentsAfter: String,
        $environmentsBefore: String,
        $environmentsEnvironmentName: String,
        $environmentsEnvironmentNameList: [String],
        $environmentsFirst: Int,
        $environmentsFulfillmentStatus: String,
        $environmentsFulfillmentStatusList: [String],
        $environmentsLast: Int,
        $environmentsOrder: [EnvironmentFilter_order],
        $environmentsProject: String,
        $environmentsProjectList: [String]
    ) {
        ...QuerySession
        ...QueryEnvironmentsList
    }
`
export const Q_QUERY_ENVIRONMENTS_LIST_SESSION_PROJECT = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_ENVIRONMENTS_LIST}
    ${F_QUERY_PROJECT}

    query EnvironmentsListSession(
        $environmentsAfter: String,
        $environmentsBefore: String,
        $environmentsEnvironmentName: String,
        $environmentsEnvironmentNameList: [String],
        $environmentsFirst: Int,
        $environmentsFulfillmentStatus: String,
        $environmentsFulfillmentStatusList: [String],
        $environmentsLast: Int,
        $environmentsOrder: [EnvironmentFilter_order],
        $environmentsProject: String!,
        $environmentsProjectList: [String],
        $projectId: ID!
    ) {
        ...QuerySession
        ...QueryEnvironmentsList
        ...QueryProject
    }
`