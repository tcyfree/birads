<?php


namespace app\admin\controller\mall;


use app\admin\model\MallGoods;
use app\admin\model\MallGoods1;
use app\admin\model\MallGoods2;
use app\admin\model\MallGoods3;
use app\admin\model\MallGoods4;
use app\admin\model\MallGoods5;
use app\admin\model\MallForreviewer;
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
            case 'forreviewer':
                $this->model = new MallForreviewer();
                break;
            case 'test':
                $this->model = new MallForreviewer();
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
        empty($row) && $this->error('Data does not exist');
        if ($this->request->isPost()) {
            $post = $this->request->post();
           
             $rule = [
                'logo'  => 'require|between:1,2'
            ];

    $message = [
      'logo.between' => 'Identification Required',
    ];
            $this->validate($post, $rule,$message);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('Save failed');
            }
            $save ? $this->success('Successfully saved') : $this->error('Save failed');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}