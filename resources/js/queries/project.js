import gql from "graphql-tag";

export const Q_PROJECT_FULL = gql`
    query($projectId: ID!) {
        project: hostingProject(id: $projectId) {
            gitUrl
            _id
            id
            name
            productionBranch
            stageBranches
            lagoonProject {
                environments
            }
            startingPoint {
                id
                name
            }
        }
    },
`

export const M_PROJECT_CREATE = gql`
    mutation($name: String!, $startingPoint: String!, $adminIds: Iterable!) {
        createProject: createHostingProject(input: {
            name: $name,
            startingPoint: $startingPoint,
            productionBranch: "main",
            stageBranches: ["develop"],
            # TODO Remove authorized* fields when we switch to teams
            authorizedAdmins: $adminIds,
            authorizedUsers: ["-10"],
        }) {
            project: hostingProject {
                id
                _id
                name
            }
        }
    }
`
