import gql from "graphql-tag";
import Model from "./Model";

export interface StartingPointInterface {
    id: string
    name: string
}

export default class StartingPoint implements Model<StartingPoint> {

    public static F_STARTING_POINT_FULL = gql`
        fragment StartingPoint on StartingPoint {
            id 
            name
        }
    `

    public static Q_STARTING_POINT_BY_ID = gql`
        ${StartingPoint.F_STARTING_POINT_FULL}
        query StartingPointByID($id: String!) {
            startingPoint(id: $id) {
                ...StartingPoint
            }
        }
    `

    public static inflate: (object: {}) => StartingPoint;
    

}