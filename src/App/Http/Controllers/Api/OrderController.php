<?php

namespace CoreCMF\Omnipay\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Core\App\Models\User;
use CoreCMF\Admin\App\Models\Config;
use CoreCMF\Omnipay\App\Models\Order;

class OrderController extends Controller
{
    /** @var MenuRepository */
    private $orderModel;
    private $configModel;
    private $userModel;

    public function __construct(Order $orderRepo, Config $configRepo, User $userPepo)
    {
        $this->orderModel = $orderRepo;
        $this->configModel = $configRepo;
        $this->userModel = $userPepo;
    }
    public function index(Request $request)
    {
        if ($request->selectSearch == 'username') {
            $request->selectSearch = 'uid';
            $user = $this->userModel->findForUser($request->inputSearch);
            if ($user) {
                $request->inputSearch = $user->id;
            }
        }
        $pageSizes = $this->configModel->getPageSizes();
        $data = resolve('builderModel')
                            ->request($request)
                            ->pageSize($this->configModel->getPageSize())
                            ->getData($this->orderModel);
        $pictureConfig = [ 'width'=>80, 'class'=>'img-responsive', 'alt'=>'支付方式'];
        //组类按钮配置
        $groupButton = [
          'buttonType'=>'group',    'apiUrl'=> route('api.admin.system.menu.edit'), 'groupKey'=> 'status', 'group'=> [
            'unpaid'=>['title'=>'关闭','type'=>'warning','icon'=>'fa fa-power-off'],
            'paid'=>['title'=>'退款','type'=>'danger','icon'=>'fa fa-history'],
          ]
        ];
        $table = resolve('builderTable')
                  ->data($data['model'])
                  ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                  ->column(['prop' => 'order_id',   'label'=> '订单ID',     'width'=> '200'])
                  ->column(['prop' => 'name',       'label'=> '订单名称', 'minWidth'=> '260'])
                  ->column(['prop' => 'fee',        'label'=> '金额(元)', 'minWidth'=> '100'])
                  ->column(['prop' => 'showStatus', 'label'=> '状态', 'minWidth'=> '120'])
                  ->column(['prop' => 'showGateway','label'=> '付款方式', 'minWidth'=> '120',    'type' => 'picture', 'config'=> $pictureConfig ])
                  ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220',    'type' => 'btn'])                       // 添加新增按钮
                  ->topButton(['buttonType'=>'default',    'apiUrl'=> route('api.admin.system.menu.delete'),'title'=>'批量导出','type'=>'info'])                         // 添加删除按钮
                  ->rightButton(['buttonType'=>'default',  'apiUrl'=> route('api.admin.system.menu.edit'),'title'=>'详情','type'=>'info','icon'=>'fa fa-eye'])
                  ->rightButton($groupButton)
                  ->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])
                  ->searchTitle('请输入搜索内容')
                  ->searchSelect(['order_id'=>'订单ID','query_id'=>'第三方ID','uid'=>'用户ID','username'=>'用户名','name'=>'订单名称','fee'=>'订单金额'])
                  ;
        return resolve('builderHtml')->title('支付订单')->item($table)->response();
    }
}
