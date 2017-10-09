import Vue from 'vue'
import App from './components/app'

window._ = require('lodash')
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import BuilderVueElement from 'builder-vue-element'
Vue.use(BuilderVueElement)

window.Echo = Echo
window.Pusher = Pusher

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App)
})
