import { forIn } from 'lodash'
export default {
  created () {
    this.broadcastHandler()
  },
  methods: {
    broadcastHandler () {
      let broadcast = this.getBroadcast()
      switch (broadcast.type) {
        case 'channel':
          this.echo = this.$root.echo.channel(broadcast.channel)
          break
        case 'private':
          this.echo = this.$root.echo.private(broadcast.channel)
          break
      }
      forIn(this.getEventHandlers(), (handler, eventName) => {
        this.echo.listen(`.${eventName}`, response => handler(response))
      })
    }
  }
}
