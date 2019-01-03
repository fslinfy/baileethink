<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Students;
use app\index\model\Learnings;

use think\Db;
class Exam extends Controller
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

             $this->today(date('Y-m-d'));
             return view('index');
      
    }
  
 public function search()
    {


       $data = input('get.');
        $exam_date=$data['examdate'];
     
        $this->today($exam_date);
          return view('index');
       return $this->view('index');
       
      
    }
public function today($filter='')
    {   //  今天考试
      
   if ($filter==date('Y-m-d')){
       $filter1="CURDATE()=w.learning_paper_date";
       $examtitle="今天考試的學生，共";
   }else
   {
      $filter1="'".$filter."'=w.learning_paper_date";
      $examtitle=$filter."考試的學生，共";

   }
     // dump(date('Y-m-d'));
    //  dump($examtitle);
      
        $db_re = Students::alias("students")
        ->field('distinct students.student_idcard_number, students.student_name,w.create_time,
        students.student_phone,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,w.learning_type,w.learning_paper_time,students.student_id ')
        ->join('v_learnings w' ,"students.student_id = w.student_id",'LEFT')
        ->where("students.delete_time IS NULL")
        ->where($filter1)    
          //  ->where('CURDATE()=w.learning_paper_date')  
        ->order('w.learning_type_order,w.car_type_order')
        ->paginate(10000000);
   
      $examtitle=$examtitle.$db_re->total().'位。';
    
     $todaylistdata0=json_encode($db_re);
    dump($todaylistdata0);
       $this->assign('todaylistdata',$db_re);
       $this->assign('todaylistdata0',$todaylistdata0);
       $this->assign('examtitle',$examtitle);
       $this->assign('exam_date',$filter);


    }


}
