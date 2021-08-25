import gql from "graphql-tag";

export const F_PROJECT_FULL = gql`
    fragment HostingProjectFields on HostingProject {
        gitUrl
        _id
        id
        name
        lagoonName
        lagoonId
        developmentEnvironmentsLimit
        productionBranch
        fulfillmentStatus
        authorizedAdmins
        authorizedUsers
        startingPoint {
            id
            name
        }
    }
`

export const Q_PROJECT_BRANCHES = gql`
    query projectBranches($projectId: ID!) {
        hostingProject(id: $projectId) {
            id
            branches
        }
    },
`

export const Q_PROJECT_LIST = gql`
    ${F_PROJECT_FULL}
    query projectList($after: String, $before: String, $perPage: Int!) {
        projects: hostingProjects(after: $after, before: $before, first: $perPage) {
            totalCount
            edges {
                cursor
                node {
                    ...HostingProjectFields
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

export const Q_PROJECT_LIST_LIGHT = gql`
query projectListLight($after: String, $before: String, $perPage: Int!) {
    projects: hostingProjects(after: $after, before: $before, first: $perPage) {
        totalCount
        edges {
            cursor
            node {
                id
                _id
                name
                lagoonName
                startingPoint {
                    id
                    name
                }
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

export const Q_PROJECT_FULL = gql`
    ${F_PROJECT_FULL}
    query project($projectId: ID!) {
        project: hostingProject(id: $projectId) {
            ...HostingProjectFields
        }
    },
`

export const M_PROJECT_CREATE = gql`
    ${F_PROJECT_FULL}
    mutation projectCreate($name: String!, $startingPoint: String!, $adminIds: Iterable!) {
        createProject: createHostingProject(input: {
            name: $name,
            startingPoint: $startingPoint,
            productionBranch: "main",
            # TODO Remove authorized* fields when we switch to teams
            authorizedAdmins: $adminIds,
            authorizedUsers: ["-10"],
        }) {
            project: hostingProject {
                ...HostingProjectFields
            }
        }
    }
`
export const Q_PROJECT_BACKUPS = gql`
    query projectBackups($projectId: ID!, $after: String, $before: String, $perPage: Int!) {
        hostingProject(id: $projectId) {
            id
            _id
            name
            productionBranch
            stageBranches
            backups(order: {dateCreated: "desc"}, first: $perPage, after: $after, before: $before) {
                edges {
                    node {
                        environmentName
                        downloadUrl
                        status
                        dateCreated
                        dateUpdated
                        taskId
                        id
                        _id
                    }
                }
            }
        }
    },
`
