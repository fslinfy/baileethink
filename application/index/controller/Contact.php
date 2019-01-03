<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Contacts;

class Contact extends Controller
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

        $listdata = Contacts::paginate(session('pagerows','','think'));
        $this->assign('listdata',$listdata);
        $this->assign('title','聯繫人');
     //   $this->assign('url','index/contact/create');
      // var_dump($data);

      //序號 	聯繫人姓名 	聯繫人電話 	備註

       
      //序號 	聯繫人姓名 	聯繫人電話 	備註
      $titlename = ['序號','聯繫人姓名','聯繫人電話','備註'];
     
      $fieldname=['contact_order','contact_name','contact_phone','contact_remark'];
      $this->assign('url','index/contact');
      $this->assign('url1','index/contact');
      $this->assign('fieldname',$fieldname);
      $this->assign('titlename',$titlename);
      $this->assign('idfield','contact_id');
       return $this->fetch();


    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
         $data['contact_name']= '';
        $data['contact_phone']= '';

        $data['contact_remark']= '';
        $data['contact_order']= '';
        $data['id']=0;
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
        $street = new Contacts;
        if($id >= 0){
            $re = Contacts::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改街道資料失敗',url('index\contact'));
        }
        
      
    }

    /**tact
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {

         
        $data0 = input('post.');
     
        $data['contact_name']= $data0['contact_name'];
        $data['contact_phone']= $data0['contact_phone'];

        $data['contact_remark']= $data0['contact_remark'];
        $data['contact_order']= $data0['contact_order'];
        $id= $data0['id'];
     
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
      // dump($data);
      // dump($data0);
      // dump($id);
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Contacts($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Contacts::get($id);
             if    ( $data0['savetype'] =='del'){
                
                     $re = $result->delete();
             }
             else
             {
              //  dump('sdfhdskjfhdskjf        save');
             //   return;
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\contact\index')); 
    
    }


}
