
export default class Token {

    type
    expires
    access
    refresh
    username
    email
    id
    roles
    data

    constructor(type, expires, access, refresh) {
        this.type = type
        this.expires = expires
        this.access = access
        this.refresh = refresh

        const base64 = this.access.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
        /** @var {} */
        const data = JSON.parse(decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join('')));

        /** https://www.iana.org/assignments/jwt/jwt.xhtml */
        this.username = data['preferred_username']
        this.email = data['email']
        this.id = parseInt(data['sub'], 10)
        this.roles = data['scopes']
        this.data = data
    }

    static inflate(json) {
        const {type, expires, access, refresh} = JSON.parse(json)
        return new Token(type, expires, access, refresh)
    }

    deflate() {
        return JSON.stringify({
            access: this.access,
            refresh: this.refresh,
            expires: this.expires,
            type: this.type,
        })
    }

    isActive() {
        const now = (new Date()).getTime() / 1000

        // Next check expires
        return !(this.data.exp && now > this.data.exp);
    }

}
