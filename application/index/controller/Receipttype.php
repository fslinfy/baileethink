<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\ReceiptTypes;

class Receipttype extends Controller
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
        $listdata = ReceiptTypes::paginate(session('pagerows','','think'));
        $this->assign('listdata',$listdata);
        $this->assign('title','收據選項');
        $this->assign('url','index/receipttype');
  
        $this->assign('url1','index/receipttype');
        $titlename = ['項目名稱' ,	'價格' ,	'排序' ,	'狀態' 	];
     
        $fieldname=['receipt_type_name','receipt_type_price','receipt_type_order','receipt_type_status'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','receipt_type_id');


       return $this->fetch();


        
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
      
        $data['receipt_type_name']='';
        $data['receipt_type_price']='';
        $data['receipt_type_status']= 1;
        $data['receipt_type_order']= '';
        
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
        // echo 'Receipttype.php edit';
        $Receipttype = new Receipttypes;
        if($id >= 0){
            $re = Receipttypes::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改收據選項失敗',url('index\receipttype'));
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
     
        $data['receipt_type_name']= $data0['receipt_type_name'];
        $data['receipt_type_price']= $data0['receipt_type_price'];

        $data['receipt_type_order']= $data0['receipt_type_order'];
        
        $id= $data0['id'];

        if(isset($data0['receipt_type_status'])){
            $data['receipt_type_status']=1;
        }else{
            $data['receipt_type_status']=0;
        }
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Receipttypes($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Receipttypes::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\receipttype\index')); 

        //
        $data = input('post.');
        $data['modifiedBy'] = session('login_username','','think');
        $Receipttype = Receipttypes::get($id);
        $re = $Receipttype->save($data);
        // var_dump($re);
        $this->redirect(url('index\R\receipttype\index')); 
    }

    
}
