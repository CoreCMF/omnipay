<template>
  <div class="omnipay-body">
    <div class="omnipay-item">
      <div class="left">
        <div class="logo"><img :src="'/vendor/omnipay/assets/' + order.gateway +'.png'"></div>
        <div class="price">应付金额：<strong><i>￥</i>{{ order.fee }}</strong></div>
        <div class="created_at">
          <p>订单名称：{{ order.name }}</p>
          <p>订单编号：{{ order.order_id }}</p>
          <p>创建时间：{{ order.created_at }}</p>
        </div>
      </div>
      <div class="right" v-if="!showQrcode">
        <div class="paid" v-if=" order.status == 'paid' ">
          <i class="fa fa-check-circle"></i>
          <span>支付成功</span>
        </div>
        <div class="unpaid" v-else>
          <i class="fa fa-info-circle"></i>
          <span>未支付</span>
        </div>
        <p>订单号：{{ order.query_id }}</p>
      </div>
      <div class="right-wechat" v-else >
        <div class="pic">
          <bve-qrcode-item
            class="qrcode"
            v-model="wechatQrcode"
            :config="wechatQrcodeConfig"
          />
        </div>
        <div class="fb"><i></i>请使用微信扫描<br>二维码以完成支付</div>
      </div>
    </div>
  </div>
</template>

<script>
import echo from '../mixins/echo'
export default {
  name: 'app',
  created () {
    if (this.isWechatBrowser) {
      this.setWechatPay()
    }
  },
  mixins: [echo],
  data () {
    return {
      wechatQrcodeConfig: {
        size: 260
      },
      responseOrder: null
    }
  },
  computed: {
    isWechatBrowser () {
      return window.config.isWechatBrowser ? window.config.isWechatBrowser : false
    },
    order () {
      return this.responseOrder ? this.responseOrder : window.config.order
    },
    // 是否显示二维码
    showQrcode () {
      return this.isWechatBrowser ? false : (this.order.gateway === 'wechat')
    },
    /**
     * [wechatQrcode 微信支付pc二维码]
     */
    wechatQrcode () {
      return window.config.wechat ? window.config.wechat.webOrder : null
    }
  },
  methods: {
    /**
     * 当是微信浏览器时 自动异步反复检测支付函数WeixinJSBridge直到找到为止
     */
    setWechatPay () {
      let _this = this
      setTimeout(function () {
        if (typeof WeixinJSBridge === 'undefined') {
          _this.setWechatPay()
        } else {
          _this.wechatPay()
        }
      }, 500)
    },
    wechatPay () {
      WeixinJSBridge.invoke(
         'getBrandWCPayRequest',
          window.config.wechat.jsOrder,
          function (res) {
            if (res.err_msg === 'get_brand_wcpay_request:ok') {
              this.responseOrder.status = 'paid'
            }
          }
      )
    },
    getBroadcast () {
      return {
        channel: 'App.User.' + window.config.userId,
        type: 'private'
      }
    },
    getEventHandlers () {
      return {
        'CoreCMF\\Omnipay\\App\\Events\\OrderStatusUpdated': response => {
          if (this.order.order_id === response.order.order_id) {
            this.responseOrder = response.order
          }
        }
      }
    }
  }
}
</script>
<style lang="scss">
body{
  margin: 0;
}
.omnipay-body{
  display: flex;
  display: -webkit-flex; /* Safari */
  justify-content: center;
  align-items: flex-start;
  background-color: #f5f7f9;
  position:absolute;
  width: 100%;
  height:100%;
}
.omnipay-item{
  display: flex;
  display: -webkit-flex; /* Safari */
  margin-top: 6.18%;
  width: 80%;
  max-width: 680px;
  border-radius:4px;
  background-color: #fff;
  padding: 2%;
  flex-wrap: wrap;
  >.left{
    width: 270px;
    padding: 5% 5% 5% 5%;
    >.logo{
      padding-bottom: 5%;
      >img{
        width: 160px;
        height: 45px;
      }
    }
    >.price{
      padding-bottom: 5%;
      font-size: 14px;
      >strong{
        font:700 30px/40px "微软雅黑";
        >i{
          font-size:24px;
        }
      }
    }
    >.created_at{
      padding: 10px 0;
      border-top:1px solid #e5e5e5;
      border-bottom:1px solid #e5e5e5;
      line-height:26px;
      >p{
        font-size: 13px;
      }
    }
  }
  >.right{
    width: 270px;
    padding: 5% 5% 5% 5%;
    display: flex;
    display: -webkit-flex; /* Safari */
    flex-direction: column;
    align-items: center;

    >.paid{
      display: flex;
      display: -webkit-flex; /* Safari */
      flex-direction: column;
      align-items: center;
      >i{
        color: #13ce66;
        font-size: 75px;
      }
      >span{
        font-size: 35px;
        margin: 5px;
      }
    }
    >.unpaid{
      display: flex;
      display: -webkit-flex; /* Safari */
      flex-direction: column;
      align-items: center;
      >i{
        color: #f9c855;
        font-size: 75px;
      }
      >span{
        font-size: 35px;
        margin: 5px;
      }
    }
    >p{
      font-size: 13px;
    }
  }
  >.right-wechat{
    width: 270px;
    padding: 5% 5% 5% 5%;
    display: flex;
    display: -webkit-flex; /* Safari */
    flex-direction: column;
    align-items: center;
    >.pic{
      width: 260px;
      height: 260px;
      background: url("/vendor/omnipay/assets/loading.gif") center center no-repeat;
      border:1px solid #e7e7e7;
    }
    >.fb{
      padding:10px;
      border-radius:3px;
      background:#f66;
      font: 700 14px/20px "微软雅黑";
      color: #fff;
      margin-top:20px;
      width: 250px;
      >i{
        width:34px;
        height:34px;
        float:left;
        background: url("/vendor/omnipay/assets/v1icon.png") 0 -40px no-repeat;
        margin:4px 20px 0;
      }
    }
  }
}
</style>
