<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Streets;

class Street extends Controller
{

    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
        # code...
        // echo 'street.php checklogin';
        // var_dump(session(''));
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

        $listdata = Streets::paginate(session('pagerows','','think'));
        $this->assign('listdata',$listdata);
        $this->assign('title','街道編號');
      
       
        $titlename = ['街道編號','中文街名','葡文街名'];
     
        $fieldname=['street_number','street_name','street_portuguese_name'];
        $this->assign('url','index/street');
        $this->assign('url1','index/street');
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','street_id');
      // var_dump($data);
       return $this->fetch('index');

   
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data['street_name']='';
        $data['street_portuguese_name']= '';
        $data['street_number']='';
        $data['id']=0;
        $data['street_status']=1;
        $this->assign('data',$data);
        return view('edit');
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
        // echo 'street.php edit';
        $street = new Streets;
        if($id >= 0){
            $re = Streets::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改街道資料失敗',url('index\street'));
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
         
        $data0 = input('post.');
     
        $data['street_name']= $data0['street_name'];
        $data['street_portuguese_name']= $data0['street_portuguese_name'];

        $data['street_number']= $data0['street_number'];
        
        $id= $data0['id'];
        if(isset($data0['street_status'])){
            $data['street_status']=1;
        }else{
            $data['street_status']=0;
        }
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Streets($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Streets::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\street\index')); 

  
    }

    
}
