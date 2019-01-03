<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
// use app\index\model\Incomes;

class Income extends Controller
{

    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
        # code...
        if (!session('login_success','','think')){
            $this->error("log in first!",url('index\login'));
        }
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // $data = Incomes::all();
        // if (!$data){
        //     if(sizeof($data) == 0){
        //         $data = 'empty';
        //     }
        // }
        // else{
        //     $data = 'empty';
        // }
        $data = 'empty';
        $this->assign('data',$data);
        // var_dump($data);
        return view();
        // $this->redirect(url('index\income\edit')); 
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data = input("post.");
        $data['createdBy'] = session('login_username','','think');
        $data['modifiedBy'] = session('login_username','','think');
        // dump($data);

        $receipt_model = new Incomes($data);
        $re = $receipt_model->save();
        if ($re){
            $this->success('添加收入統計成功',url('index\income'));
        }else{
            $this->error('添加收入統計失敗',url('index\income'));
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit()
    {
        //
        // echo 'Income.php edit';
        $Income = new Incomes;

        $data = Incomes::all();
        $data_size = sizeof($data);
        if($data_size >= 1){
            $re = $data[$data_size - 1];
        }
        // var_dump($re);

        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改收入統計失敗',url('index\income'));
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = input('post.');
        $data['modifiedBy'] = session('login_username','','think');
        $Income = Incomes::get($id);
        $re = $Income->save($data);
        // var_dump($re);
        $this->redirect(url('index\income\index')); 
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $Income = Incomes::get($id);
        // $Income['modifiedBy'] = session('login_username','','think');
        $re = $Income->delete();
        if ($re){
            // $this->success('刪除收入統計成功',url('index\income'));
            $this->redirect(url('index\income'));
        }else{
            $this->error('刪除收入統計失敗',url('index\income'));
        }
        return $re;
    }
}
