import { forIn } from 'lodash'
export default {
  data () {
    return {
      broadcast: {
        channel: null,
        type: 'channel'
      }
    }
  },
  created () {
    forIn(this.getEventHandlers(), (handler, eventName) => {
      this.$root.echo
          .private(this.channel)
          .listen(`.${eventName}`, response => handler(response))
    })
  }
}
