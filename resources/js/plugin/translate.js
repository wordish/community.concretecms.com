import Vue from "vue"
import {strings} from "../strings";

export default class Translate {
    /**
     *
     * @param {Vue} Vue
     */
    static install(Vue) {
        Vue.$t = strings
        Vue.prototype.$t = strings
    }
}