<template>
  <div class="omnipay-body">
    <div class="omnipay-item">
      <div class="left">
        <div class="logo"><img src="/static/assets/wechat.png"></div>
        <div class="price">应付金额：<strong><i>￥</i>38.00</strong></div>
        <div class="order"><p>创建时间：2017-10-02 11:41:16</p></div>
      </div>
      <div class="right">
        <div class="pic">
          <img src="https://zsstatus.tuanimg.com/pay/weixin/rq?pay=weixin%3A%2F%2Fwxpay%2Fbizpayurl%3Fpr%3DUm2LVjh">
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
    >.order{
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
    >.pic{
      width: 267px;
      height: 267px;
      background: url("/static/assets/loading.gif") center center no-repeat;
      >img{
        width: 267px;
        height: 267px;
        border:1px solid #e7e7e7;
      }
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
