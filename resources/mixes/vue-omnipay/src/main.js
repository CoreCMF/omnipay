import Vue from 'vue'
import App from './components/app'

import BuilderVueElement from 'builder-vue-element'
Vue.use(BuilderVueElement)
/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App)
})
