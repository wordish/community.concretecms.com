import gql from "graphql-tag";

export const Q_PROJECT_FULL = gql`
    query($projectId: ID!) {
        project(id: $projectId) {
            gitUrl
            _id
            id
            name
            productionBranch
            stageBranches
            lagoonProject {
                environments
            }
        }
    },
`
