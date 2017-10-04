import Vue from 'vue'
import App from './components/app'

import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import BuilderVueElement from 'builder-vue-element'
Vue.use(BuilderVueElement)
window.Pusher = Pusher
window.Echo = new Echo({
  host: 'corecmf.dev',
  broadcaster: 'pusher',
  key: '22317447f90c25aef80ce73cca5648fe'
  encrypted: true
})
window.Echo.private('App.User.1').listen('order.status.updated', (e) => {
  console.log('dazles01')
  console.log(e)
}).listen('OrderStatusUpdated', (e) => {
  console.log('dazles02')
  console.log(e)
})
/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App)
})
