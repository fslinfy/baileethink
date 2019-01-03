<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\index\model\Receipts;
use app\index\model\ReceiptDetails;

class Receipt extends Controller
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
       

        session('act','0','think');

        $listdata = Receipts::where('receipt_status',1)->order('receipt_date', 'desc')->paginate(session('pagerows','','think')) ;
        $this->assign('listdata',$listdata);
        $this->assign('title','收據記錄');

        $this->assign('url','');
        $this->assign('url1','index/receipt');
        $titlename = ['收據日期','收據號碼','收據内容','總金額','经办人'];
      
        $fieldname=['receipt_date','receipt_number','receipt_content',
        'receipt_total_price','modifiedBy'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','receipt_id');

        return $this->fetch();
    
    }
    public function receiptedit()
    {

       // dump("sdfjg");
    }
    public function learningreceipt()
    {
       $id=$_GET["learningid"];
       $result_row = Db::name('receipts')
             ->where('learning_id', '=',$id)
             ->where('receipt_status', '=',1)
             ->select();

       if (!$result_row)
       {
        $studentid= Db::name('learnings')
        ->where('learning_id', $id)
        ->value('student_id');

       // dump($studentid);
       }else{
//dump($result_row);
        $studentid=$result_row[0]['student_id'];

       } 
       if($studentid=='0')
       {
        $studentid=session('student_id','','think');
       }
       // $listdata = Receipts::order('receipt_date', 'desc')->paginate(session('pagerows','','think')) ;
        $this->assign('studentid',$studentid);
        $this->assign('learningid',$id);
        
        $this->assign('listdata',$result_row);
        $this->assign('title','收據記錄');
        $this->assign('url','index/receipt/create');
      //  dump($listdata);
        return $this->fetch('learningreceipt');

        
    }
    public function indexmx()
    {
       // dump(session('login_username','','think'));
       // return;
        $receiptid=$_GET['receiptid'];;
        $learningid=$_GET['learningid'];   
        $act=$_GET['act'];   
       // dump($act);
       // dump($learningid);
        if ($receiptid>0){
           $sqlstring='SELECT * FROM receipts where  receipt_id='.$receiptid ;
        }else
        {
            $sqlstring='SELECT * FROM receipts where  receipt_status=0 and learning_id='.$learningid.' limit 1 ';
            
        }
        $data = Db::query($sqlstring);
       // $data=Receipts::where('receipt_id','=',$receiptid);
       if ($data){
          $receiptid=$data[0]["receipt_id"];
         }
      $data=Receipts::get($receiptid);
      if (!$data){

      $receiptid=0;
     $data['modifiedBy'] = session('login_username','','think');
      $data['receipt_date'] =date('Y-m-d',time());
      $data['receipt_id'] =0 ;
      $data['learning_id'] =$learningid ;
      $data['receipt_pay_type'] ='现金' ;
      $data['detail_item'] = '' ;
      $data['receipt_content'] = '' ;
      $data['receipt_total_price'] = 0 ;
      $data['receipt_number'] = '' ;
      $data['receipt_status'] =0 ;
      }
    //dump($data);
   // return;

        $plist = Db::query('select name from poplist where type="收付方式"');
        
        $listdata = Db::query('SELECT * FROM receipt_types');
        $this->assign('act',$act);
        $this->assign('listdata',$listdata);
        $this->assign('plist',$plist);

        $mxlistdata = Db::query('SELECT receipt_details.*,receipt_types.receipt_type_name as detail_item
        FROM receipt_details,receipt_types 
        where receipt_details.receipt_type_id=receipt_types.receipt_type_id 
        and    receipt_details.receipt_id='.$receiptid);// ReceiptDetails::where('receipt_id',$id);
    
       $this->assign('receiptid',$receiptid);
       $this->assign('learningid',$learningid);
        $this->assign('mxlistdata',$mxlistdata);
        $this->assign('data',$data);
        $this->assign('title','收據記錄');
        $this->assign('url','index/receipt/create');
        


        return $this->fetch('edit');



    

        
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

        $receipt_model = new Receipts($data);
        $re = $receipt_model->save();
        if ($re){
            header('location: '.$_SERVER['HTTP_REFERER']);
           // url('index\receipt');
         //   $this->success('添加收據記錄成功',url('index\receipt'));
        }else{
            $this->error('添加收據記錄失敗',url('index\receipt'));
        }
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {  
         
       $act= session('act','','think');
        $plist = Db::query('select name from poplist where type="收付方式"');
        
        $listdata = Db::query('SELECT * FROM receipt_types');
        $this->assign('act',$act);
        $this->assign('listdata',$listdata);
        $this->assign('plist',$plist);
        $receipt = new Receipts;
        if($id >= 0){
            $re = Receipts::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);

            $mxlistdata = Db::query('SELECT receipt_details.*,receipt_types.receipt_type_name as detail_item
            FROM receipt_details,receipt_types 
            where receipt_details.receipt_type_id=receipt_types.receipt_type_id 
            and    receipt_details.receipt_id='.$id);// ReceiptDetails::where('receipt_id',$id);
        $this->assign('title','title');
    
        $this->assign('learningid',0);
            $this->assign('mxlistdata',$mxlistdata);
            return view('edit');
        }
        else{
            $this->error('修改收據記錄失敗',url('index\receipt'));
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
     //dump($id);
        $data = input('get.');
        dump($data);
        return;
        $data['modifiedBy'] = session('login_username','','think');
        $receipt = Receipts::get($id);
        $re = $receipt->save($data);
        // var_dump($re);
        $this->redirect(url('index\receipt\index')); 
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
        $receipt = Receipts::get($id);
        $receipt['modifiedBy'] = session('login_username','','think');
        $re = $receipt->delete();
        if ($re){
            // $this->success('刪除收據記錄成功',url('index\receipt'));
            $this->redirect(url('index\receipt'));
        }else{
            $this->error('刪除收據記錄失敗',url('index\receipt'));
        }
        return $re;
    }
    public function mxdelete($id,$learningid,$receiptid)
    {

       // $learningid= Db::name('receipts')->where('receipt_id', $receiptid)->value('learning_id');

       // Db::table('receipt_details')->delete($id);
        $url=url('index\receipt\indexmx')."?learningid=".$learningid."&receiptid=".$receiptid."&act=1";
       // $this->redirect($url);
        
        
        $receipt = Receiptdetails::get($id);
     
       
        $receipt['modifiedBy'] = session('login_username','','think');
        $re = $receipt->delete();
        $this->redirect($url);
        return;

      
    }
    public function mxinsert(Request $request, $id)
    {
        //
        $form = input('post.');
     
        $savetype=$form['savetype'];
      
        
        $receiptid=$form['receipt_id'];
        $data['receipt_content'] = $form['receipt_content'];
        $data['receipt_date'] = $form['receipt_date'];
        $data['receipt_number'] = $form['receipt_number'];
        $data['receipt_pay_type'] = $form['receipt_pay_type'];
        $data['modifiedBy'] = $form['modifiedBy'];
        $data['update_time'] =date('Y-m-d H:i:s',time());
        
        if( $receiptid==0){
            $data['createdBy'] = $form['modifiedBy'];
            $data['learning_id'] = $form['learning_id'];
            $data['create_time'] =date('Y-m-d H:i:s',time());
            $receiptid=  Db::name('receipts')->insertGetId($data);
        }else
        {
            if ($savetype=="save"){
                $data['receipt_status'] =1;
                $receipt = Receipts::get($receiptid);
                $re = $receipt->save($data);
              
                $url=url('index\receipt\indexmx')."?learningid=".$form['learning_id']."&receiptid=".$receiptid."&act=1";
                $this->redirect($url);
                return;



            }else{
            $receipt = Receipts::get($receiptid);
            $re = $receipt->save($data);
            }


        }

        $mxdata['receipt_id'] =$receiptid;
        $mxdata['receipt_type_id'] = $form['receipt_type_id'];
        $mxdata['detail_quantity'] = $form['detail_quantity'];
        $mxdata['detail_price'] = $form['detail_price'];
        $mxdata['detail_sum'] = $form['detail_sum'];
        $mxdata['detail_note'] = $form['detail_note'];
        $mxdata['detail_item'] = $form['detail_item'];
       // dump($mxdata);
      //  return;

       
        $mxdatamodel = new Receiptdetails($mxdata);
        $re = $mxdatamodel->save();
        $url=url('index\receipt\indexmx')."?learningid=".$form['learning_id']."&receiptid=".$receiptid."&act=1";
        $this->redirect($url);
        return;
  
    }

}
