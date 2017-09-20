<?php

namespace CoreCMF\Omnipay\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function __construct(){
    }
    public function index()
    {
        $tabs = ['alipay'=>'支付宝','wechat'=>'微信支付','unionpay'=>'银联支付'];
        $form = resolve('builderForm')
                  ->tabs($tabs)
                  // ->data($configs)
                  // ->apiUrl('submit',route('api.admin.system.system.update'))
                  ;
        return resolve('builderHtml')->title('支付配置')->item($form)->response();
    }
}
