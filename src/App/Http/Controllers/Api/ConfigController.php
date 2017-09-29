<?php

namespace CoreCMF\Omnipay\App\Http\Controllers\Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Core\App\Models\Upload;
use CoreCMF\Omnipay\App\Models\Config;

class ConfigController extends Controller
{
    private $configModel;
    private $uploadModel;

    public function __construct(Config $configPro, Upload $UploadRepo){
       $this->configModel = $configPro;
       $this->uploadModel = $UploadRepo;
       $this->builderForm = resolve('builderForm')->item(['name' => 'status',         'type' => 'switch',   'label' => '开关']);
    }
    public function index(Request $request)
    {
        $gateway = $request->tabIndex? $request->tabIndex: 'alipay';
        $configs = $this->configModel->where('gateway', '=', $gateway)->first();
        $this->builderForm->item(['name' => 'gateway', 'type' => 'hidden']);
        $this->publicGatewayForm($gateway,$configs);//根据不同网关添加不同 form item
        $this->publicForm();//添加公共form item
        $this->builderForm->apiUrl('submit',route('api.admin.omnipay.config.update'))->itemData($configs);
        return resolve('builderHtml')->title('支付配置')->item($this->builderForm)->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])->response();
    }
    /**
     * [update 配置更新]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        if ($this->configModel->where('gateway', '=', $request->gateway)->update($request->all())) {
          $message = [
                      'title'     => '保存成功',
                      'message'   => '系统设置保存成功!',
                      'type'      => 'success',
                  ];
        }
        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [file 文件上传]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function file(Request $request)
    {
        $imageData = Input::all();
        $extension = ['pem','cer','pfx'];
        config(['filesystems.default' => 'local']);
        $response = $this->uploadModel->fileUpload($imageData,'omnipay/certificates',$extension);

        return response()->json($response, 200);
    }
    /**
     * [publicGatewayForm 网关公共form]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    public function publicGatewayForm($gateway,$configs)
    {
        $upload = [
            'extension' => ['pem','pfx','cer'],
            'maxSize' => 10*1024,//大小限制10kb
            'type' => 'file',
            'uploadUrl' => route('api.admin.omnipay.config.file'),
        ];
        switch ($gateway) {
          case 'alipay':
            $driver = [
                'Alipay_AopPage' => '电脑网站支付 - new',
                'Alipay_AopApp' => 'APP支付 - new',
                'Alipay_AopF2F' => '当面付 - new',
                'Alipay_AopWap' => '手机网站支付 - new',
                'Alipay_LegacyApp' => 'APP支付',
                'Alipay_LegacyExpress' => '即时到账',
                'Alipay_LegacyWap' => '手机网站支付'
            ];
            $other = ['RSA2'=>'RSA2','RSA'=>'RSA','MD5'=>'MD5'];
            return $this->builderForm->item(['name' => 'driver',         'type' => 'select',   'label' => '默认驱动', 'placeholder' => '驱动','options'=>$driver])
                    ->item(['name' => 'app_id', 'type' => 'text',    'label' => 'appId',          'placeholder' => 'appId'])
                    ->item(['name' => 'seller_id',  'type' => 'text',     'label' => 'sellerEmail',     'placeholder' => '支付宝商家账号Email'])
                    ->item(['name' => 'other',         'type' => 'select',   'label' => '加密方式', 'placeholder' => '加密方式','options'=>$other])
                    ->item(array_merge(['name' => 'public_key', 'label' => 'alipayPublicKey',
                        'placeholder' => '支付宝公钥','fileName'=> $this->getFileName($configs->public_key)
                    ],$upload))
                    ->item(array_merge(['name' => 'private_key','label' => 'privateKey',
                        'placeholder' => '自己生成的密钥','fileName'=> $this->getFileName($configs->private_key)
                    ],$upload));
            break;
          case 'wechat':
            $driver = [
                'WechatPay' => '微信支付通用网关',
                'WechatPay_App' => '微信APP支付网关',
                'WechatPay_Native' => '微信原生扫码支付支付网关',
                'WechatPay_Js' => '微信网页、公众号、小程序支付网关',
                'WechatPay_Pos' => '微信刷卡支付网关',
                'WechatPay_Mweb' => '微信H5支付网关'
            ];
            return $this->builderForm->item(['name' => 'driver',         'type' => 'select',   'label' => '默认驱动', 'placeholder' => '驱动','options'=>$driver])
                    ->item(['name' => 'app_id', 'type' => 'text',    'label' => 'appId',          'placeholder' => '开发者ID(AppID)'])
                    ->item(['name' => 'seller_id', 'type' => 'text',     'label' => 'mchId',     'placeholder' => '微信商户号'])
                    ->item(['name' => 'other',     'type' => 'text',     'label' => 'AppSecret',     'placeholder' => '开发者密码(AppSecret)'])
                    ->item(array_merge(['name' => 'public_key','type' => 'file',     'label' => 'apiclientCert',
                        'placeholder' => '微信公钥','fileName'=> $this->getFileName($configs->public_key)
                    ],$upload))
                    ->item(array_merge(['name' => 'private_key','type' => 'file',    'label' => 'apiclientKey',
                        'placeholder' => '微信密钥','fileName'=> $this->getFileName($configs->private_key)
                    ],$upload));
            break;
          case 'unionpay':
            $driver = [
                'UnionPay_Express' => '银联全产品网关（PC，APP，WAP支付）',
                'UnionPay_LegacyMobile' => '银联老网关（APP）',
                'UnionPay_LegacyQuickPay' => '银联老网关（PC)'
            ];
            return $this->builderForm->item(['name' => 'driver',         'type' => 'select',   'label' => '默认驱动', 'placeholder' => '驱动','options'=>$driver])
                    ->item(['name' => 'app_id', 'type' => 'text',    'label' => 'merId',          'placeholder' => '商户号(merId)'])
                    ->item(array_merge(['name' => 'private_key','type' => 'file',    'label' => 'certPath',
                          'placeholder' => '商户私钥证书(certPath)','fileName'=> $this->getFileName($configs->private_key)
                    ],$upload))
                    ->item(['name' => 'other',     'type' => 'text',     'label' => 'certPassword','placeholder' => '商户私钥证书密码(certPassword)'])
                    ->item(array_merge(['name' => 'public_key','type' => 'file',     'label' => 'certDir',
                          'placeholder' => '银联公钥证书(certDir)','fileName'=> $this->getFileName($configs->public_key)
                    ],$upload));
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
                  ->item(['name' => 'return_url',     'type' => 'text',     'label' => '回调地址','disabled'=>true])
                  ->item(['name' => 'notify_url',     'type' => 'text',     'label' => '通知地址','disabled'=>true])
                  ->config('labelWidth','120px')
                  ;
    }
    public function getFileName($value){
        $upload = new Upload();
        $uploadObject = $upload->getUploadWhereFirst($value);
        return ($uploadObject->code == 404 )? null: $uploadObject->name;
    }
}
