import gql from "graphql-tag";
import fulfillmentStatus from "./FulfillmentStatus";

export interface ProjectInterface {
    gitUrl: string
    gitPath: string
    _id: string
    id: string
    name: string
    lagoonName: string
    lagoonId: number
    developmentEnvironmentsLimit: number
    productionBranch: string
    fulfillmentStatus: fulfillmentStatus
    authorizedAdmins: number[]
    authorizedUsers: number[]
    startingPoint: {
        id: string
        name: string
    }
}

export default class Project {

    public static F_PROJECT_FULL = gql`
        fragment Project on HostingProject {
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
    `

    public static Q_PROJECT_BY_ID = gql`
        ${Project.F_PROJECT_FULL}
        query ProjectByName($id: String!) {
            hostingProject(id: $id) {
                ...Project
            }
        }
    `

}