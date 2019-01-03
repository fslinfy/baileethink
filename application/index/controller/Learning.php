<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Learnings;
use think\Db;
class Learning extends Controller
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
        $data = Learnings::all();
        if(sizeof($data) == 0){
            $data = 'empty';
        }
        $this->assign('data',$data);
        // var_dump($data);
        return view();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    { $resultlist = Db::query('select name from poplist where type="考試結果" order by id');
        $plist = Db::query('select * from learning_types order by learning_type_order');
        $poplist = Db::query('select car_type,car_type_id,car_type_type from car_types order by car_type_order ');
        $coachlist = Db::query('select coach_name  from coachs where coach_status=1 order by coach_order');
        $this->assign('coachlist',$coachlist);
        $this->assign('resultlist',$resultlist);
        $this->assign('poplist',$poplist);
        $this->assign('plist',$plist);
     
        $data['student_id']=session('student_id','','think');
        $data['car_type_id']=0;
        $data['learning_type_id']= 0;
        $data['car_type']= '';
           $data['learning_license_type']= '';
        $data['learning_paper_date']= '';
        $data['learning_paper_time']='';
        $data['learning_paper_result']= '';
    $data['learning_pay_price']=0;
        $data['learning_price']=0;
        $data['coach_name']= '';
        $data['learning_status']=0;
        $data['learning_id']=0;

 
        $data['id']=0;
    
         $this->assign('data',  $data);
         return view('edit');


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

        // var_dump($data);        

        $data['createdBy'] = session('login_username','','think');
        $data['modifiedBy'] = session('login_username','','think');
        // dump($data);
       // if($data['learning_paper_result']=='不合格' || $data['learning_drive_result'] == '不合格'){
        if($data['learning_paper_result']=='不合格'){
            $data['learning_valid'] = false;
        }
        else{
            $data['learning_valid'] = true;
        }
        if($data['learning_type_id']<3){
            $data['coach_name'] ="";
        }
        $receipt_model = new Learnings($data);
        $re = $receipt_model->save();
        if ($re){
            $this->success('添加學習進度成功',url('index\Student\read').'?id='.$data['student_id']);
        }else{
            $this->error('添加學習進度失敗',url('index\Student\read').'?id='.$data['student_id']);
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
        $poplist = Db::query('select * from car_types order by car_type_order ');
        $this->assign('poplist',$poplist);
        return view();
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
        // echo 'Learning.php edit';

     
    
      // dump($plist);
    
        $Learning = new Learnings;
        if($id >= 0){
            $re = Learnings::get($id);
            $re['learning_paper_time']=substr($re['learning_paper_time'],0,5);
            $student_id = $re['student_id'];
        }
        if($re){
            $resultlist = Db::query('select name from poplist where type="考試結果" order by id');
            
           // $plist = Db::query('select name from poplist where type="考試项目" order by id');
            $plist = Db::query('select * from learning_types order by learning_type_order');
            
            $poplist = Db::query('select car_type,car_type_id,car_type_type from car_types order by car_type_order ');
         //  dump($poplist);
         $coachlist = Db::query('select coach_name  from coachs where coach_status=1 order by coach_order');
         $this->assign('coachlist',$coachlist);
           $this->assign('resultlist',$resultlist);
           $this->assign('plist',$plist);
            $this->assign('poplist',$poplist);
            $this->assign('data',$re);
            return view();
                }
        else{
            if($student_id){
                $this->error('修改學習進度失敗',url('index\Student\read').'?id='.$student_id);
            }
            else{
                $this->error('修改學習進度失敗',url('index\worklog\index'));
            }
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {

           
        $data0 = input('post.');
     
        foreach($data0 as $fieldname=>$value) {
            if (($fieldname<>'id') &&($fieldname<>'savetype'))
                    {
                    $data[$fieldname]=$data0[$fieldname];
                }
          }

     
        
        $id= $data0['id'];


        if(isset($data0['learning_status'])){
            $data['learning_status']=1;
        }else{
            $data['learning_status']=0;
        }
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
       
        if($data['learning_paper_result']=='不合格'){
            $data['learning_valid'] = false;
        }
        else{
            $data['learning_valid'] = true;
        }
        if($data['learning_type_id']<3){
        //    $data['coach_name'] ="";
        }

        $data['student_id']=session('student_id','','think');
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
          
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Learnings($data);
            $re = $student_model->save();
         }
         else
         {

            $result = Learnings::get($id);

      

             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
              
                $re = $result->save($data);
              //  dump($id);
               // dump($data);
               // return;
             }
         }      
         $this->redirect(url('index\student\read')."?id=".$data['student_id']); 




        

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
        $Learning = Learnings::get($id);
        $Learning['modifiedBy'] = session('login_username','','think');
        $student_id = $Learning['student_id'];
        $re = $Learning->delete();

        if ($re){
            // $this->success('刪除學習進度成功',url('index\Learning'));
            // $this->redirect(url('index\Learning'));
            $this->redirect(url('index\Student\read').'?id='.$student_id); 
        }else{
            if($student_id){
                $this->error('刪除學習進度失敗',url('index\Student\read').'?id='.$student_id);
            }
            else{
                $this->error('刪除學習進度失敗',url('index\worklog\index'));
            }
        }
        return $re;
    }
}
