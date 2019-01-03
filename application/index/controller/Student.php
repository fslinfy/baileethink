<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Students;
use app\index\model\Learnings;
use app\index\model\Receipts;
use app\index\model\Licenses;
use think\Db;
class Student extends Controller
{

    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
    
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

             $this->getresult('delete_time IS NULL');
             return view('index');
      
    }
 public function search()
    {


       $data = input('get.');
     //  dump($data );
     //  return;
       $keywords=$data['keywords'];
       if  ($keywords=="")
       {
         //   return $this->index();
$filter='delete_time IS NULL';
       }
       else
       {

$filter='student_idcard_number  LIKE "%'.$keywords.'%"';
$filter=$filter.' or student_name  LIKE "%'.$keywords.'%"';
$filter=$filter.' or student_phone  LIKE "%'.$keywords.'%"';
$filter=$filter.' or concat(student_foreign_surname," ",student_foreign_name)   LIKE "%'.$keywords.'%"';
$filter=$filter.' or concat(student_foreign_surname,",",student_foreign_name)   LIKE "%'.$keywords.'%"';
       }

     $this->getresult($filter);
return view('index');

      
    }


public function getresult($filter)
    {


        session('worklog',false,'think');
        session('student_id','0','think');
        $listdata = Students::where($filter)
        ->order('student_id', 'desc')->paginate(session('pagerows','','think'));

        $this->assign('listdata',$listdata);
        $this->assign('title','學生檔案');
        $this->assign('url','index/student');
        $this->assign('url1','index/student');		 	
        $titlename = ['聯絡電話 ','建立時間','更新時間'];
      
        $fieldname=['student_phone', 'create_time','update_time'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','student_id');
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $visionlist = Db::query('select name,namevalue from poplist where type="vision" order by id');
        $languagelist = Db::query('select name,namevalue from poplist where type="language" order by id');

        //dump($languagelist);
        //return;
        $this->assign('visionlist',$visionlist);
        $this->assign('languagelist',$languagelist);
        $poplist = Db::query('select * from streets  order by street_number ');
    
        $this->assign('poplist',$poplist);
       
       
        $data['student_idcard_number']='';
        $data['student_type']= '';
        $data['student_valid_date']= '';
        $data['student_card_department']= '';
        $data['student_foreign_surname']= '';
 
        $data['student_foreign_name']= '';
        $data['student_name']= '';
        $data['student_sex']= '';
        $data['student_brithday']= '';
        $data['student_brith_location']= '';

        $data['student_nationality']= '';
        $data['student_language']= '';
        $data['student_phone']= '';
        $data['street_id']= '';
        $data['student_street_number']= '';
        $data['student_proguese_location']= '';

        $data['student_proguese_location2']= '';
        $data['student_location']= '';
        $data['student_eyesight']= '';
        $data['student_remark']= '';
        $data['student_never_overdate']= '';
 

        $data['student_old']= '';
 

        $data['id']=0;
    
         $this->assign('data',  $data);
         return view('edit');
     
    }



    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id,Request $request)
    {

        session('student_id',$id,'think');
        session('act','2','think');
   
     $worklog=$request->param('worklog');
        if($id >= 0){
            $re = Students::get($id);


            

 
          $cartypelist = Db::query('select * from car_types order by car_type_order ');
          $this->assign('cartypelist',$cartypelist);
          $receiptdata = Db::table('receipts')->alias('b')
          ->where('b.receipt_status',1)
          ->where('b.student_id',$id)
          ->order('b.receipt_date', 'desc') ->select();;
          $this->assign('receipt_data',$receiptdata);

          
        $licensedata = Db::table('licenses')->alias('b')
    
        ->where('b.student_id',$id)
        ->order('b.createdBy', 'desc') ->select();;
        $this->assign('license_data',$licensedata);

        $msgs_data = Db::table('msgs')->alias('b')
    
        ->where('b.student_id',$id)
        ->order('b.create_time', 'desc') ->select();;
        $this->assign('msgs_data',$msgs_data);


/*
         $learning_re = Db::table('learnings')->alias('a')
              ->field('c.learning_type,  b.car_type,     b.car_type_order,c.learning_type_order,
              a.learning_id,a.car_type_id,a.student_id,a.learning_paper_date,a.learning_pay_price,
              a.learning_paper_time,a.learning_paper_result,a.learning_relate_info,a.learning_valid,
              a.coach_name,a.learning_price,a.learning_status,a.learning_type_id ')
              ->join("car_types b","a.car_type_id =b.car_type_id")
         
              ->join("learning_types c","a.learning_type_id =c.learning_type_id")
              ->where('a.student_id',$id)
              //->distinct(true)
              //->buildSql();
              ->select();
*/
              $learning_re = Db::table('v_learnings')
              ->where('student_id',$id)
              ->order('create_time')
              ->select();
        
        }
        if($re){
            $this->assign('data',$re);
            $this->assign('worklog',$worklog);
            $this->assign('studentid',$id);

            if($learning_re){
                $this->assign('learning_data',$learning_re);
            } else {
                $this->assign('learning_data','empty');

            }
            //dump($id);
            return view();
        }
        else{
            $this->error('查看學生資料失敗',url('index\student'));
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
        //
        // echo 'student.php edit';
        // var_dump($id);
        $student = new Students;
        if($id >= 0){
            $visionlist = Db::query('select name,namevalue from poplist where type="vision" order by id');
            $languagelist = Db::query('select name,namevalue from poplist where type="language" order by id');
            $this->assign('visionlist',$visionlist);
            $this->assign('languagelist',$languagelist);
            $poplist = Db::query('select * from streets order by street_number ');

            $this->assign('poplist',$poplist);
            $re = Students::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            return view();
        }
        else{
            // $this->error('修改學生資料失敗',url('index\student'));
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
     
       foreach($data0 as $fieldname=>$value) {
         if (($fieldname<>'id') &&($fieldname<>'savetype'))
             {
                 $data[$fieldname]=$data0[$fieldname];
             }
       }
     
         
       if(isset($data0['student_never_overdate'])){
           $data['student_never_overdate']=1;
       }else{
           $data['student_never_overdate']=0;
       }

       if(isset($data0['student_old'])){
        $data['student_old']=1;
    }else{
        $data['student_old']=0;
    }

       $id= $data0['id'];
    
       $data['update_time'] = date("Y-m-d H:i:sa");
       $data['modifiedBy'] = session('login_username','','think');
       
        if ($id=='0')
        {
       
           $data['createdBy'] = session('login_username','','think');
        
           $data['create_time'] = date("Y-m-d H:i:sa");
       
          // dump($data);
           $student_model = new students($data);
           $re = $student_model->save();
        }
        else
        {
           $result = students::get($id);
            if    ( $data0['savetype'] =='del'){
                    $re = $result->delete();
            }
            else
            {
               $re = $result->save($data);
            }
        }      
        if (session('worklog','','think')){
            $this->redirect(url('index\student\read')."?id=".$id);
        }else{
        $this->redirect(url('index\student\index'));
        }
    }

    public function licensedelete($id)
    {
        

        $student_id=  session('student_id','','think');
        $receipt = Licenses::get($id);
     
       
       
        $re = $receipt->delete();
        $url=url('index\student\read')."?id=".$student_id;
        $this->redirect($url);

    }    
    public function licenseinsert()
    {
        

        $id=  session('student_id','','think');
        $data = input('post.');
      
     
        
     
            $data['createdBy'] = session('login_username','','think');
            $data['student_id'] = $id;
            $data['createdAt'] =date('Y-m-d H:i:s',time());
            $receiptid=  Db::name('licenses')->insertGetId($data);
            dump($data);
           // return;
         
        $url=url('index\student\read')."?id=".$id;
        $this->redirect($url);
       
    }
  

}
