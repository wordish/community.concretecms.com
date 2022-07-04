
// I know this is terrible, but I don't want to have double the bedrock dependencies so I'm linking to this here.
// @todo - Remove concrete_cms_hosting since we're not using it, add bedrock to this repo, and change this
// import to import it from here
import VueManager from '../../../../concrete_cms_hosting/build/node_modules/@concretecms/bedrock/assets/cms/js/vue/Manager'

VueManager.bindToWindow(window)

import SkylineInstallationProgress from './frontend/components/SkylineInstallationProgress'
import VueEllipseProgress from 'vue-ellipse-progress';

window.Concrete.Vue.createContext('frontend', {
    SkylineInstallationProgress,
    VueEllipseProgress
}, 'frontend')

window.Concrete.Vue.activateContext('frontend', function (Vue, config) {
    new Vue({
        el: 'div[vue-skyline]',
        components: config.components
    })
})