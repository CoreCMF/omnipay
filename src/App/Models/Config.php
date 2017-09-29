<?php

namespace CoreCMF\Omnipay\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;
use CoreCMF\Core\App\Models\Upload;

class Config extends Model
{
    public $table = 'omnipay_configs';

    protected $fillable = [];

    public function getStatusAttribute($value)
    {
        return (boolean)$value;
    }
    /**
     * [configRegister 注册配置信息]
     * @return [type] [description]
     */
    public function configRegister()
    {
        if (Schema::hasTable('omnipay_configs')) {
            $this->gatewayRegister();
        }
    }
    /**
     * [gatewayRegister 网关配置注册]
     * @return [type] [description]
     */
    public function gatewayRegister()
    {
        $this->where('status', true)->get()->map(function ($item) {
          $upload = new Upload();
          $private_key = storage_path($upload->getUploadWhereFirst($item->private_key)->path);
          $public_key = storage_path($upload->getUploadWhereFirst($item->public_key)->path);
          switch ($item->gateway) {
            case 'unionpay':
              config(['laravel-omnipay.gateways.'.$item->gateway.'.driver' => $item->driver]);
                config(['laravel-omnipay.gateways.'.$item->gateway.'.options' => [
                    'merId' => $item->app_id,
              		  'certPath' => $private_key,
              		  'certPassword' => $item->other,
              		  'certDir'=> $public_key,
              		  'returnUrl' => $item->return_url,
              		  'notifyUrl' => $item->notify_url
                ]]);
            break;
          }
        });
        dd(config('laravel-omnipay.gateways'));
        /**
         * [加载云磁盘配置]
         * @var [type]
         */
        // $this->all()->map(function ($disks) {
        //     $transport = $disks->transport? 'https': 'http';
        //     switch ($disks->driver) {
        //       case 'oss':
        //           $isCName = strstr($disks->domain,"aliyuncs.com")? false: true;//不包含阿里云自动启用自定义域名
        //           config(['filesystems.disks.'.$disks->disks => [
        //                 'driver'			=> 'oss',
        //                 'accessKeyId'		=> $disks->access_id,
        //                 'accessKeySecret' 	=> $disks->access_key,
        //                 'endpoint'			=> $disks->domain,
        //                 'isCName'			=> $isCName,
        //                 'securityToken'		=> null,
        //                 'bucket'            => $disks->bucket,
        //                 'timeout'           => '5184000',
        //                 'connectTimeout'    => '10',
        //                 'transport'     	=> $transport,//如果支持https，请填写https，如果不支持请填写http
        //                 'max_keys'          => 1000,//max-keys用于限定此次返回object的最大数，如果不设定，默认为100，max-keys取值不能大于1000
        //           ]]);
        //         break;
        //       case 'qiniu':
        //         config(['filesystems.disks.'.$disks->disks => [
        //             'driver'        => 'qiniu',
        //             'domain'        => $disks->domain,//你的七牛域名
        //             'access_key'    => $disks->access_id,//AccessKey
        //             'secret_key'    => $disks->access_key,//SecretKey
        //             'bucket'        => $disks->bucket,//Bucket名字
        //             'transport'     => $transport,//如果支持https，请填写https，如果不支持请填写http
        //         ]]);
        //         break;
        //       case 'upyun':
        //         config(['filesystems.disks.'.$disks->disks => [
        //           'driver'        => 'upyun',
        //           'domain'        => $disks->domain,//你的upyun域名
        //           'username'      => $disks->access_id,//UserName
        //           'password'      => $disks->access_key,//Password
        //           'bucket'        => $disks->bucket,//Bucket名字
        //           'timeout'       => 130,//超时时间
        //           'endpoint'      => null,//线路
        //           'transport'     => $transport,//如果支持https，请填写https，如果不支持请填写http
        //         ]]);
        //         break;
        //       case 'cos':
        //         config(['filesystems.disks.'.$disks->disks => [
        //           'driver'			=> 'cos',
        //           'domain'            => $disks->domain,      // 你的 COS 域名
        //           'app_id'            => $disks->app_id,
        //           'secret_id'         => $disks->access_id,
        //           'secret_key'        => $disks->access_key,
        //           'region'            => $disks->region,        // 设置COS所在的区域
        //           'transport'     	  => $transport,      // 如果支持 https，请填写 https，如果不支持请填写 http
        //           'timeout'           => 60,          // 超时时间
        //           'bucket'            => $disks->bucket,
        //         ]]);
        //         break;
        //     }
        // });
        // /**
        //  * [$defaultDisks 挂载默认磁盘]
        //  * @var [type]
        //  */
        // $default = $this->where('status',1)->first();
        // if ($default) {
        //     config(['filesystems.default' => $default->disks]);
        // }
    }
}
