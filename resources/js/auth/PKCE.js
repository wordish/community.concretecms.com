import sha256 from 'crypto-js/sha256';
import Base64 from 'crypto-js/enc-base64';

export function createVerifier() {
    const length = Math.max(43, Math.min(128, Math.floor(Math.random() * 28) + 43));
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';
    const charactersLength = characters.length;

    var result = '';

    while (result.length < length) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }

    return result;
}

export function createChallenge(verifier) {
    /** @ts-ignore Allow string to be passed here */
    return urlEncode(sha256(verifier))
}

export function urlEncode(code) {
    /** @ts-ignore Allow string to be passed here */
    const before = Base64.stringify(code)
    const after = before
        .replace(/=/g, '')
        .replace(/\+/g, '-')
        .replace(/\//g, '_')

    return after;
}
