<?php

namespace CoreCMF\Omnipay\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Omnipay\App\Models\Config;

class ConfigController extends Controller
{
    private $configModel;

    public function __construct(Config $configPro){
       $this->configModel = $configPro;
       $this->builderForm = resolve('builderForm')->item(['name' => 'status',         'type' => 'switch',   'label' => '开关']);
    }
    public function index(Request $request)
    {
        $gateway = $request->tabIndex? $request->tabIndex: 'alipay';
        $configs = $this->configModel->where('gateway', '=', $gateway)->first();
        $this->publicGatewayForm($gateway);//根据不同网关添加不同 form item
        $this->publicForm();//添加公共form item
        $this->builderForm->apiUrl('submit',route('api.admin.omnipay.config.update'))->itemData($configs);
        return resolve('builderHtml')->title('支付配置')->item($this->builderForm)->response();
    }
    /**
     * [update 配置更新]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {

    }
    /**
     * [file 文件上传]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function file(Request $request)
    {

    }
    /**
     * [publicGatewayForm 网关公共form]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    public function publicGatewayForm($gateway)
    {
        switch ($gateway) {
          case 'alipay':
            return $this->builderForm->item(['name' => 'app_id', 'type' => 'text',    'label' => 'appId',          'placeholder' => 'appId'])
                    ->item(['name' => 'seller_id',  'type' => 'text',     'label' => 'sellerEmail',     'placeholder' => '支付宝商家账号Email'])
                    ->item(['name' => 'public_key', 'type' => 'file',     'label' => 'alipayPublicKey',    'placeholder' => '支付宝公钥', 'uploadUrl'=>route('api.admin.omnipay.config.file')])
                    ->item(['name' => 'private_key','type' => 'file',     'label' => 'privateKey',    'placeholder' => '自己生成的密钥', 'uploadUrl'=>route('api.admin.omnipay.config.file')]);
            break;
          case 'wechat':
            return $this->builderForm->item(['name' => 'app_id', 'type' => 'text',    'label' => 'appId',          'placeholder' => '开发者ID(AppID)'])
                    ->item(['name' => 'seller_id', 'type' => 'text',     'label' => 'mchId',     'placeholder' => '微信商户号'])
                    ->item(['name' => 'other',     'type' => 'text',     'label' => 'AppSecret',     'placeholder' => '开发者密码(AppSecret)'])
                    ->item(['name' => 'public_key','type' => 'text',     'label' => 'public_key',    'placeholder' => '微信公钥'])
                    ->item(['name' => 'private_key','type' => 'text',    'label' => 'private_key',    'placeholder' => '微信密钥']);
            break;
          case 'unionpay':
            return $this->builderForm->item(['name' => 'app_id', 'type' => 'text',    'label' => 'merId',          'placeholder' => '商户号(merId)'])
                    ->item(['name' => 'private_key','type' => 'text',    'label' => 'certPath',    'placeholder' => '商户私钥证书(certPath)'])
                    ->item(['name' => 'other',     'type' => 'text',     'label' => 'certPassword','placeholder' => '商户私钥证书密码(certPassword)'])
                    ->item(['name' => 'public_key','type' => 'text',     'label' => 'certDir',    'placeholder' => '银联公钥证书(certDir)']);
            break;
        }
    }
    /**
     * [publicForm 公共form]
     * @return [type] [description]
     */
    public function publicForm()
    {
        $tabs = ['alipay'=>'支付宝','wechat'=>'微信支付','unionpay'=>'银联支付'];
        $this->builderForm->tabs($tabs)
                  ->item(['name' => 'driver',         'type' => 'text',     'label' => 'driver'])
                  ->item(['name' => 'return_url',     'type' => 'text',     'label' => '回调地址','disabled'=>true])
                  ->item(['name' => 'notify_url',     'type' => 'text',     'label' => '通知地址','disabled'=>true])
                  ->config('labelWidth','120px')
                  ;
    }
}
