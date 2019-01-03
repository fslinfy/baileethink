<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Groups;
use think\Db;

class Group extends Controller
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
               $listdata = Groups::order('groups.group_order')
               ->paginate(session('pagerows','','think'));//返回对象
               
               
                  $this->assign('listdata',$listdata);
                  $this->assign('title','用戶群組');
                  $this->assign('url','index/group');
                  $this->assign('url1','index/group');
                  $titlename = ['群組名稱' ,	'群組序號'	];
     
                  $fieldname=['group_name','group_order'];
                
                  $this->assign('fieldname',$fieldname);
                  $this->assign('titlename',$titlename);
                  $this->assign('idfield','group_id');
                  
                
                 return $this->fetch();

     
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data['group_name']='';
        $data['group_order']= '';
        $data['id']= 0;
        $this->assign('data',$data);
        return view('edit');
    }



    
    public function edit($id)
    {
        //
        // echo 'Group.php edit';
        $Group = new Groups;
        if($id >= 0){
            $re = Groups::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改收據選項失敗',url('index\group'));
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
     
        $data['group_name']= $data0['group_name'];
        $data['group_order']= $data0['group_order'];
       
           
        
        $id= $data0['id'];
     
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Groups($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Groups::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\group\index')); 

     
    }

}
