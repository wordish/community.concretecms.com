import gql from "graphql-tag";

const fields = `
    environmentName
    taskType
    taskId
    group
    dateCreated
    dateUpdated
    fulfillmentStatus
    dateFulfilled
    dateStarted
    status
    id
    _id
`;

export const F_TASK_FULL = gql`
    fragment TaskFields on EnvironmentTask {
        ${fields}
    }
`

export const F_BACKUP_FULL = gql`
    fragment BackupFields on Backup {
        ${fields}
    }
`

export const F_RESTORE_FULL = gql`
    fragment RestoreFields on Restore {
        ${fields}
        backup {
            id
        }
    }
`

export const F_INSTALL_FULL = gql`
    fragment InstallFields on Install {
        ${fields}
    }
`

export const Q_TASK_FULL = gql`
    ${F_TASK_FULL}
    query($taskId: ID!) {
        environmentTask(id:$taskId) {
            ...TaskFields
        }
    }
`

export const Q_TASKS_BY_PROJECT = gql`
    ${F_TASK_FULL}
    query deploysByProject($projectId: String!, $environment: String, $group: String) {
        environmentTasks(project: $projectId, environmentName: $environment, group: $group, order:{dateCreated:"desc"}) {
            pageInfo {
                startCursor
                endCursor
                hasNextPage
                hasPreviousPage
            }
            totalCount
            edges {
                node {
                    ...TaskFields
                }
            }
        }
    }
`

export const M_TASK_CREATE_BACKUP = gql`
    ${F_BACKUP_FULL}
    mutation createBackup($projectId: String!, $environment: String!) {
        createBackup(input: {environmentName: $environment, project: $projectId}) {
            backup {
                ...BackupFields
            }
        }
    }
`;

export const M_TASK_CREATE_RESTORE = gql`
    ${F_RESTORE_FULL}
    mutation createRestore($projectId: String!, $environment: String!, $backupId: String!) {
        createRestore(input: {environmentName: $environment, project: $projectId, backup: $backupId}) {
            restore {
                ...RestoreFields
            }
        }
    }
`;

export const M_TASK_CREATE_INSTALL = gql`
    ${F_INSTALL_FULL}
    mutation createInstall($projectId: String!, $environment: String!) {
        createInstall(input: {environmentName: $environment, project: $projectId}) {
            install {
                ...InstallFields
            }
        }
    }
`;
