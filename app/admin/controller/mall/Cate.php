<?php


namespace app\admin\controller\mall;


use app\admin\model\MallCate;
use app\admin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use app\admin\model\MallGoods1;

/**
 * Class Admin
 * @package app\admin\controller\system
 * @ControllerAnnotation(title="商品分类管理")
 */
class Cate extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new MallGoods1();
        $this->assign('checkList', [0 => '', 1 =>"良", 2 => "恶"]);
        $this->assign('checkList', [0 => '',1 =>"良", 2 => "恶"]);
        $this->assign('areas', [1 =>"dz", 2 => "ts", 3 => "sw"]);

        $this->assign('users', (new \app\admin\model\SystemAdmin())->where('id','>',1)->where("delete_time is null")->column('username', 'id'));
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
            if (session('admin.id') == 1) {
                $count = $this->model
                    // ->withJoin('cate', 'LEFT')
                    // ->withJoin(['users'])
                    ->where($where)
                    ->count();
                $list = $this->model
                    // ->withJoin('cate', 'LEFT')
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            } else {
                $count = $this->model
                    // ->withJoin('cate', 'LEFT')
                    // ->withJoin(['users'])
                    ->where($where)
                    ->where('virtual_sales', session('admin.id'))
                    ->count();
                $list = $this->model
                    // ->withJoin('cate', 'LEFT')
                    ->where($where)
                    // ->withJoin(['users'])
                    ->where('virtual_sales', session('admin.id'))
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();    
            }

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