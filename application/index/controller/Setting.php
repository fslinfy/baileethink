<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Settings;

class Setting extends Controller
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
        // $data = Settings::all();
        // if(sizeof($data) == 0){
        //     $data = 'empty';
        // }
        // $this->assign('data',$data);
        // var_dump($data);
        // return view();
        $this->redirect(url('index\setting\edit')); 
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

        $receipt_model = new Settings($data);
        $re = $receipt_model->save();
        if ($re){
            $this->success('添加基本設定成功',url('index\setting'));
        }else{
            $this->error('添加基本設定失敗',url('index\setting'));
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
        // echo 'Setting.php edit';
        $Setting = new Settings;

        $data = Settings::all();
        $data_size = sizeof($data);

        if($data_size >= 1){
            $re = $data[$data_size - 1];
        }
   
        if($re){
          
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改基本設定失敗',url('index\setting'));
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
       $id=$data['setting_id'];
        $data['modifiedBy'] = session('login_username','','think');
        $Setting = Settings::get($id);
        $re = $Setting->save($data);
        // var_dump($re);
        $this->redirect(url('index\setting\index')); 
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
        $Setting = Settings::get($id);
        $Setting['modifiedBy'] = session('login_username','','think');
        $re = $Setting->delete();
        if ($re){
            // $this->success('刪除基本設定成功',url('index\setting'));
            $this->redirect(url('index\setting'));
        }else{
            $this->error('刪除基本設定失敗',url('index\setting'));
        }
        return $re;
    }
}
