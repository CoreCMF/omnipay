<?php

namespace CoreCMF\Omnipay\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Core\Support\Http\Request as CoreRequest;
use CoreCMF\Omnipay\App\Models\Config;

class ConfigController extends Controller
{
    private $configModel;

    public function __construct(Config $configPro){
       $this->configModel = $configPro;
    }
    public function index(CoreRequest $request)
    {
        $gateway  = $request->get('tabIndex','wechat');
        $configs = $this->configModel->where('gateway', '=', $gateway)->first();
        $tabs = ['alipay'=>'支付宝','wechat'=>'微信支付','unionpay'=>'银联支付'];
        $form = resolve('builderForm')
                  ->tabs($tabs)
                  ->item(['name' => 'appId',     'type' => 'text',      'label' => 'appId',      'value'=> $configs->app_id])
                  ->item(['name' => 'returnUrl',     'type' => 'text',     'label' => '回调地址',     'value'=> $configs->return_url,'disabled'=>true])
                  ->item(['name' => 'notifyUrl',     'type' => 'text',     'label' => '通知地址',     'value'=> $configs->notify_url,'disabled'=>true])
                  // ->data($configs)
                  // ->apiUrl('submit',route('api.admin.system.system.update'))
                  ;
        return resolve('builderHtml')->title('支付配置')->item($form)->response();
    }
}
