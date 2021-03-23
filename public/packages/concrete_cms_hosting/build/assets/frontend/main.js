/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

import VueManager from '../../node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager'

VueManager.bindToWindow(window)

import HostingControlPanel from './components/HostingControlPanel'
import NewHostingProjectSelector from './components/NewHostingProjectSelector'

window.Concrete.Vue.createContext('frontend', {
    HostingControlPanel,
    NewHostingProjectSelector
}, 'frontend')

Concrete.Vue.activateContext('frontend', function (Vue, config) {
    new Vue({
        el: 'div[vue-hosting]',
        components: config.components
    })
})
