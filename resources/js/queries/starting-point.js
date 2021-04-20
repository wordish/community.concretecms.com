import gql from "graphql-tag";

export const Q_STARTING_POINTS_FULL = gql`
    query {
        startingPoints {
            id
            name
        }
    },
`
