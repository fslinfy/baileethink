<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Doctors;

class Doctor extends Controller
{

    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
        # code...
        // echo 'doctor.php checklogin';
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
        $listdata = Doctors::paginate(session('pagerows','','think'));
        $this->assign('listdata',$listdata);
        $this->assign('title','醫生資料');
        $this->assign('url','index/doctor');
        $this->assign('url1','index/doctor');
        

        $titlename = ['中文姓名' ,	'外文姓名' ,	'姓别' ,	'執照編號', 	'狀態' 	];
     
        $fieldname=['doctor_name','doctor_foreign_name','doctor_sex','doctor_license','doctor_status'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','doctor_id');
       return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

        $data['doctor_name']= '';
        $data['doctor_foreign_name']='';
        $data['doctor_sex']='男';
        $data['doctor_order']='' ;
        $data['doctor_license']= '';
        $data['doctor_phone']='';
        $data['id']=0;
        
        $data['doctor_status']=1;
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
        // echo 'doctor.php edit';
        $doctor = new Doctors;

        if($id >= 0){
            $re = Doctors::get($id);
            $re['id']= $id;
        }
        if($re){
            $this->assign('data',$re);
            return view('edit');
        }
        else{
            $this->error('修改醫生資料失敗',url('index\doctor'));
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
      //  dump( $data0 );
       // return;
        $data['doctor_name']= $data0['doctor_name'];
        $data['doctor_foreign_name']= $data0['doctor_foreign_name'];
        $data['doctor_sex']=$data0['doctor_sex'];
        $data['doctor_order']= $data0['doctor_order'];
        $data['doctor_license']= $data0['doctor_license'];
        $data['doctor_phone']= $data0['doctor_phone'];
        $id= $data0['id'];
        if(isset($data0['doctor_status'])){
            $data['doctor_status']=1;
        }else{
            $data['doctor_status']=0;
        }
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Doctors($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Doctors::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\doctor\index'));  
         
           
    }

  

}
