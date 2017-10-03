<template>
  <div class="omnipay-body">
    <div class="omnipay-item">
      <div class="left">
        <div class="logo"><img src="/static/assets/wechat.png"></div>
        <div class="price">应付金额：<strong><i>￥</i>{{ price }}</strong></div>
        <div class="created_at">
          <p>订单名称：{{ name }}</p>
          <p>订单编号：{{ order_id }}</p>
          <p>创建时间：{{ created_at }}</p>
        </div>
      </div>
      <div class="right">
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
export default {
  name: 'app',
  created () {
    console.log(window.config)
  },
  data () {
    return {
      wechatQrcodeConfig: {
        size: 260
      }
    }
  },
  computed: {
    gateway () {
      return window.config.data.order.gateway
    },
    price () {
      return window.config.data.order.fee.toFixed(2)
    },
    created_at () {
      return window.config.data.order.created_at
    },
    order_id () {
      return window.config.data.order.order_id
    },
    name () {
      return window.config.data.order.name
    },
    /**
     * [wechatQrcode 微信支付pc二维码]
     * @return {[type]} [description]
     */
    wechatQrcode () {
      return window.config.data.wechat.webOrder
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
  width: 680px;
  border-radius:4px;
  background-color: #fff;
  padding: 2%;
  >.left{
    width: 45%;
    padding: 5% 5% 5% 5%;
    >.logo{
      padding-bottom: 5%;
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
    width: 55%;
    padding: 5% 5% 5% 5%;
    display: flex;
    display: -webkit-flex; /* Safari */
    flex-direction: column;
    align-items: center;
    >.pic{
      width: 260px;
      height: 260px;
      background: url("/static/assets/loading.gif") center center no-repeat;
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
        background: url("/static/assets/v1icon.png") 0 -40px no-repeat;
        margin:4px 20px 0;
      }
    }
  }
}
</style>
