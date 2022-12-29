<?php


namespace app\admin\controller\mall;


use app\admin\model\MallGoods;
use app\admin\model\MallGoods1;
use app\admin\model\MallGoods2;
use app\admin\model\MallGoods3;
use app\admin\model\MallGoods4;
use app\admin\model\MallGoods5;
use app\admin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\admin\controller\mall
 * @ControllerAnnotation(title="商城商品管理")
 */
class Goods extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        switch (session('admin.username')) {
            case 'test1':
                $this->model = new MallGoods1();
                break;
            case 'test2':
                $this->model = new MallGoods2();
                break;
            case 'test3':
                $this->model = new MallGoods3();
                break;
            case 'test4':
                $this->model = new MallGoods4();
                break;
            case 'test5':
                $this->model = new MallGoods5();
                break;
            default:
                $this->model = new MallGoods();
        }
        $this->assign('checkList', ["","True","False"]);
        // $this->assign('checkList', [1 =>"True", 2 => "False"]);
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->withJoin('cate', 'LEFT')
                ->where($where)
                ->count();
            $list = $this->model
                ->withJoin('cate', 'LEFT')
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
           
             $rule = [
                'logo'  => 'require|between:1,2',
                'sales'   => 'require|between:1,2'
            ];

    $message = [
      'logo.between' => '第一次分类必填',
      'sales.between' => '第二次分类必填',
    ];
            $this->validate($post, $rule,$message);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="入库")
     */
    public function stock($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['total_stock'] = $row->total_stock + $post['stock'];
                $post['stock'] = $row->stock + $post['stock'];
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}