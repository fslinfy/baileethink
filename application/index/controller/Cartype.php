<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\CarTypes;


class Cartype extends Controller
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
       

        $listdata = CarTypes::order('car_type_order')->paginate(session('pagerows','','think'));
        $this->assign('listdata',$listdata);
        $this->assign('title','車類');
        $this->assign('url','index/cartype');
        $this->assign('url1','index/cartype');
    
      
       
        $titlename = ['車類' ,	'車類類型', 	'狀態', 	'排序' 	];
     
        $fieldname=['car_type','car_type_type','car_type_status','car_type_order'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','car_type_id');
       return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data['car_type']='';
        $data['car_type_id']='';
        $data['car_type_type']= '';
        $data['car_type_status']= 1;
        $data['car_type_order']= '';
        $data['remark1']= '';
        $data['remark2']= '';
        $data['remark3']= '';
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
        //
        // echo 'Cartype.php edit';
        $Cartype = new Cartypes;
        if($id >= 0){
            $re = Cartypes::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改車輛類型失敗',url('index\Cartype'));
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

        $data0 = input('post.');
     
        $data['car_type']= $data0['car_type'];
        $data['car_type_type']= $data0['car_type_type'];
        $data['car_type_status']= $data0['car_type_status'];
        $data['car_type_order']= $data0['car_type_order'];
        $data['remark1']= $data0['remark1'];
        $data['remark2']= $data0['remark2'];
        $data['remark3']= $data0['remark3'];
        $id= $data0['id'];
        if(isset($data0['car_type_status'])){
            $data['car_type_status']=1;
        }else{
            $data['car_type_status']=0;
        }
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Cartypes($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Cartypes::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\cartype\index')); 


    
    }

 
}
