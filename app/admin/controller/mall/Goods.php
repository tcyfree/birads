<?php


namespace app\admin\controller\mall;


use app\admin\model\MallGoods;
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
        $this->model = new MallGoods();
        $this->assign('checkList', [1 =>"良", 2 => "恶"]);
        $this->assign('checkList', [1 =>"良", 2 => "恶"]);
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
                    ->withJoin(['users'])
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
                    ->withJoin(['users'])
                    ->where($where)
                    ->where('virtual_sales', session('admin.id'))
                    ->count();
                $list = $this->model
                    // ->withJoin('cate', 'LEFT')
                    ->where($where)
                    ->withJoin(['users'])
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

    /**
     * @NodeAnotation(title="执行Pyhon")
     */
    public function execPython($url = 1)
    {
        // $cmd = system("python /Applications/phpstudy/WWW/birads/app/admin/controller/mall/classificateTemp.py $url");
        // $cmd = shell_exec("sudo python classificateTemp.py 1");
        $cmd = exec("http://127.0.0.1:5000/v1/book/1");
        // header("content-type:text/html;charset=utf-8");
        // $order="python ".getcwd()."/Applications/phpstudy/WWW/birads/app/admin/controller/mall/classificateTemp.py 1";
        // $exec = shell_exec("python"); 
        var_dump($cmd);

        echo "$cmd";
        $data = [
            'code'  => 0,
            'msg'   => '11s1333',
            'data'  => $cmd,
        ];
        return json($data);
    }

}