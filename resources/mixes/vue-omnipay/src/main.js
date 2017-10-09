import Vue from 'vue'
import App from './components/app'

window._ = require('lodash')
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import BuilderVueElement from 'builder-vue-element'
Vue.use(BuilderVueElement)

window.Pusher = Pusher

/* eslint-disable no-new */
new Vue({
  el: '#app',
  created () {
    let options = {
      broadcaster: 'socket.io',
      host: 'dashboard.spatie.be:6001'
    }
    this.echo = new Echo(options)
  },
  render: h => h(App)
})
