import gql from "graphql-tag";
import {F_QUERY_SESSION} from "./session";
import {F_QUERY_PROJECT} from "./project";

export const F_TASK_FULL = gql`
    fragment TaskFull on EnvironmentTask {
        environmentName
        group
        taskType
        taskId
        dateCreated
        dateUpdated
        fulfillmentStatus
        dateFulfilled
        dateStarted
        id
        _id
    }
`;

export const F_QUERY_TASK = gql`
    ${F_TASK_FULL}

    fragment QueryTask on Query {
        task: environmentTask(id: $taskId) {
            ...TaskFull
        }
    }
`

export const F_QUERY_TASKS_LIST = gql`
    ${F_TASK_FULL}
    fragment QueryTasksList on Query {
        tasks: environmentTasks(
            group: $tasksGroup
            after: $tasksAfter
            before: $tasksBefore
            environmentName: $tasksEnvironmentName
            environmentName_list: $tasksEnvironmentNameList
            first: $tasksFirst
            fulfillmentStatus: $tasksFulfillmentStatus
            fulfillmentStatus_list: $tasksFulfillmentStatusList
            last: $tasksLast
            order: $tasksOrder
            project: $tasksProject
            project_list: $tasksProjectList
        ) {
            totalCount
            edges {
                cursor
                node {
                    ...TaskFull
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

export const Q_QUERY_TASKS_LIST_SESSION = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_TASKS_LIST}
    
    query TasksListSession(
        $tasksGroup: String,
        $tasksType: String,
        $tasksAfter: String,
        $tasksBefore: String,
        $tasksEnvironmentName: String,
        $tasksEnvironmentNameList: [String],
        $tasksFirst: Int,
        $tasksFulfillmentStatus: String,
        $tasksFulfillmentStatusList: [String],
        $tasksLast: Int,
        $tasksOrder: [EnvironmentTaskFilter_order],
        $tasksProject: String,
        $tasksProjectList: [String]
    ) {
        ...QuerySession
        ...QueryTasksList
    }
`
export const Q_QUERY_TASKS_LIST_SESSION_PROJECT = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_TASKS_LIST}
    ${F_QUERY_PROJECT}

    query TasksListSession(
        $tasksGroup: String,
        $tasksAfter: String,
        $tasksBefore: String,
        $tasksEnvironmentName: String,
        $tasksEnvironmentNameList: [String],
        $tasksFirst: Int,
        $tasksFulfillmentStatus: String,
        $tasksFulfillmentStatusList: [String],
        $tasksLast: Int,
        $tasksOrder: [EnvironmentTaskFilter_order],
        $tasksProject: String!,
        $tasksProjectList: [String],
        $projectId: ID!
    ) {
        ...QuerySession
        ...QueryTasksList
        ...QueryProject
    }
`