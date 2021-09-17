import gql from "graphql-tag";

export const F_SESSION_FULL = gql`
    fragment SessionFull on Session {
        id
        username
        email
        roles
    }
`;

export const F_QUERY_SESSION = gql`
    ${F_SESSION_FULL}

    fragment QuerySession on Query {
        session: currentSession {
            ...SessionFull
        }
    }
`