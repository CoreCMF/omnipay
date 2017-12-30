import Vue from 'vue'
import App from './components/app'
import 'font-awesome/css/font-awesome.css'

window._ = require('lodash')
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import ElementUI from 'element-ui'
import BuilderVueElement from 'builder-vue-element'
Vue.use(ElementUI)
Vue.use(BuilderVueElement)

window.Pusher = Pusher

/* eslint-disable no-new */
new Vue({
  el: '#app',
  created () {
    let options = {
      broadcaster: window.config.broadcast.broadcaster,
      host: window.config.broadcast.host
    }
    this.echo = new Echo(options)
  },
  render: h => h(App)
})
