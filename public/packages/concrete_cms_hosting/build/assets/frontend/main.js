/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

import VueManager from '../../../../../packages/concrete_cms_theme/build/node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager'
VueManager.bindToWindow(window)

import NewHostingProjectSelector from './components/NewHostingProjectSelector.vue'

window.Concrete.Vue.createContext('frontend', {
    NewHostingProjectSelector
}, 'frontend')

Concrete.Vue.activateContext('frontend', function (Vue, config) {
    new Vue({
        el: 'div[vue-hosting]',
        components: config.components
    })
})
