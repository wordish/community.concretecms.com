import gql from "graphql-tag";

export const M_ENVIRONMENT_DEPLOY = gql`
    mutation($branch: String!, $projectId: Int!) {
        deployProject(input:{
            project:$projectId
            branch:$branch
        }) {
            project {
                lagoonProject {
                    environments
                }
            }
        }
    }
`;
