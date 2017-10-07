import Vue from 'vue'
import App from './components/app'

import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import BuilderVueElement from 'builder-vue-element'
Vue.use(BuilderVueElement)
window.Laravel = {
  csrfToken: window.config.csrfToken
}
window.Pusher = Pusher
window.echo = new Echo({
  broadcaster: 'socket.io',
  host: 'http://corecmf.dev:6001'
  // broadcaster: 'pusher',
  // key: '059ed477866087f99056b595fbd80c80',
})
console.log(window.echo.private('App.User.1'))
window.echo.private('App.User.1')
.listen('.CoreCMF\\Omnipay\\App\\Events\\OrderStatusUpdated', (e) => {
  console.log('dazles02')
  console.log(e)
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App)
})
