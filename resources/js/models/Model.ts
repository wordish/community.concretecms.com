export default interface Model<Subtype> {
    inflate: (object: {}) => Subtype
}