<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Logs;

class Log extends Controller
{

    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
       // $this->"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
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
       // $data = Logs::all();

        $listdata = Logs::paginate(session('pagerows','','think'));

    //  if(sizeof($data) == 0){
        //    $data = 'empty';
      //  }
        $this->assign('listdata',$listdata);
        $this->assign('title','操作日志');
        $this->assign('url','');
        $this->assign('url1','');
        $titlename = ['操作內容' ,	'操作人' ,	'動作' ,	'操作結果', 	'對應路徑' ,	'操作時間'	];
     
        $fieldname=['log_content','log_staff','log_behave','log_result','log_path','log_datetime'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','log_id');



        return view();
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
        $data['log_staff'] = session('login_username','','think');
        // $data['log_staff'] = session('login_username','','think');
        // dump($data);

        $receipt_model = new Logs($data);
        $re = $receipt_model->save();
        if ($re){
            $this->success('添加操作日誌成功',url('index\log'));
        }else{
            $this->error('添加操作日誌失敗',url('index\log'));
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
    public function edit($id)
    {
        //
        // echo 'Log.php edit';
        $Log = new Logs;
        if($id >= 0){
            $re = Logs::get($id);
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改操作日誌失敗',url('index\log'));
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
        $data['log_staff'] = session('login_username','','think');
        $Log = Logs::get($id);
        $re = $Log->save($data);
        // var_dump($re);
        $this->redirect(url('index\log\index')); 
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
        $Log = Logs::get($id);
        $Log['log_staff'] = session('login_username','','think');
        $re = $Log->delete();
        if ($re){
            // $this->success('刪除操作日誌成功',url('index\log'));
            $this->redirect(url('index\log'));
        }else{
            $this->error('刪除操作日誌失敗',url('index\log'));
        }
        return $re;
    }
}
