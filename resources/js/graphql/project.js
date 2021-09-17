import gql from "graphql-tag";
import {F_QUERY_SESSION, F_SESSION_FULL} from "./session";

export const F_PROJECT_FULL = gql`
    fragment ProjectFull on HostingProject {
        gitUrl
        gitPath
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
`;

export const F_PROJECT_BRANCHES = gql`
    fragment ProjectBranches on HostingProject {
        id
        branches
    }
`;

export const F_QUERY_PROJECT = gql`
    ${F_PROJECT_FULL}
    
    fragment QueryProject on Query {
        project: hostingProject(id: $projectId) {
            ...ProjectFull
        }
    }
`
export const F_QUERY_PROJECT_BRANCHES = gql`
    ${F_PROJECT_BRANCHES}

    fragment QueryProject on Query {
        project: hostingProject(id: $id) {
            ...ProjectBranches
        }
    }
`

export const F_QUERY_PROJECT_LIST = gql`
    ${F_PROJECT_FULL}

    fragment QueryProjectList on Query {
        projects: hostingProjects(
            first: $projectsFirst 
            last: $projectsLast 
            after: $projectsAfter 
            before: $projectsBefore 
            name: $projectsName 
            order: $projectsOrder
        ) {
            totalCount
            edges {
                cursor
                node {
                    ...ProjectFull
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
`

export const Q_PROJECT_LIST_SESSION = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_PROJECT_LIST}

    query ProjectList(
        $projectsFirst: Int
        $projectsLast: Int
        $projectsAfter: String
        $projectsBefore: String
        $projectsName: String
        $projectsOrder: [HostingProjectFilter_order]
    ) {
        ...QuerySession
        ...QueryProjectList
    }
`

export const Q_PROJECT_SESSION = gql`
    ${F_QUERY_SESSION}
    ${F_QUERY_PROJECT}
    
    query Project($projectId: ID!) {
        ...QuerySession
        ...QueryProject
    }
    
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