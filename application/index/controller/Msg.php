<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
//use app\index\model\Students;
//use app\index\model\Learnings;
use app\index\model\Msgs;
use think\Db;
class Msg extends Controller
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

             $this->today();
             return view('index');
      
    }
  
 public function search()
    {

/*
       $data = input('get.');
        $Msg_date=$data['Msgdate'];
     
        $this->today($Msg_date);
          return view('index');
       return $this->view('index');
       */
      
    }
public function today()
    {   //  今天考试
     /* 
   if ($filter==date('Y-m-d')){
       $filter1="CURDATE()=w.learning_paper_date";
       $Msgtitle="今天考試的學生，共";
   }else
   {
      $filter1="'".$filter."'=w.learning_paper_date";
      $Msgtitle=$filter."考試的學生，共";

   }
   */
      
        $db_re = Msgs::paginate(session('pagerows','','think'));
   
     // $Msgtitle=$Msgtitle.$db_re->total().'位。';
    
     //$todaylistdata0=json_encode($db_re);
   // dump($db_re);
       $this->assign('msglistdata',$db_re);
      // $this->assign('todaylistdata0',$todaylistdata0);
     //  $this->assign('Msgtitle',$Msgtitle);
     //  $this->assign('Msg_date',$filter);


    }


}
