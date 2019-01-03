<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
// use app\index\model\Worklogs;
use app\index\model\Students;


class Worklog extends Controller
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
       
        return $this->readall();


    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        // return view();
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
        // $data = input("post.");
        // $data['createdBy'] = session('login_username','','think');
        // $data['modifiedBy'] = session('login_username','','think');
        // // dump($data);

        // $receipt_model = new Worklogs($data);
        // $re = $receipt_model->save();
        // if ($re){
        //     $this->success('添加收入統計成功',url('index\Worklog'));
        // }else{
        //     $this->error('添加收入統計失敗',url('index\Worklog'));
        // }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
    }

    public function readall()
    {   //  全部
        
    
       // $subsql=$this->subsqlstring();
      

        $db_re = Students::alias("students")
        ->field('distinct students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id ')
        ->join('v_learnings w' ,"students.student_id = w.student_id",'LEFT')
        ->where("students.delete_time IS NULL")
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        //->buildSql();
        ->paginate(session('pagerows','','think'));//返回对象
     //  dump( $db_re);       
      // return;
         $titlename = ['聯絡電話','建立日期'];
        $fieldname=['student_phone','create_time'];
        $title = '全部學生记录，共'.$db_re->total().'位。';
        $this->assign('listdata',$db_re);
        $this->assign('title',$title);
        $this->assign('titlename',$titlename);
        $this->assign("fieldname",$fieldname);
        $this->assign("worklog",'all');
        
        return $this->fetch('worklog/read');
    }

    public function read1()
    {   //  以下為過去七天的新學生，共位。
      
        $this->assign("worklog",'1');
       // $subsql=$this->subsqlstring();
         $db_re = Students::alias("students")
         ->field('students.student_idcard_number, students.student_name,
         students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
         w.car_type,students.student_id ')
         ->join("(select distinct student_id,car_type from v_learnings  o ) w"   ,"students.student_id = w.student_id",'LEFT')
         ->where("students.delete_time IS NULL")
         ->where('DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= DATE(students.create_time)')    
         ->order('students.student_id,students.create_time')
         //->buildSql();
          ->paginate(session('pagerows','','think'));//返回对象
  
         //dump($db_re);
         //return;


         $fieldname=['student_phone','create_time'];

        $titlename = ['聯絡電話','建立日期'];
        
        $title = '以下為過去七天的新學生，共'.$db_re->total().'位。';

       // dump($db_re->total());
       // return;
        $this->assign('listdata',$db_re);
        $this->assign('title',$title);
        $this->assign('titlename',$titlename);
        $this->assign("fieldname",$fieldname);
        return $this->fetch('worklog/read');

    }

    public function read2()
    {   //  十天新稟
        $this->assign("worklog",'2');
        $title = '十天新稟';
      
       
         $subsql = Students::alias("students")
        ->field('distinct students.student_id ')
        ->join('v_learnings w' ,"students.student_id = w.student_id",'LEFT')
        ->where("students.delete_time IS NULL")
         ->where("w.learning_type_id",1)
       ->where('DATE_SUB(CURDATE(), INTERVAL 10 DAY) <= DATE(w.create_time)')    
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->buildSql();

  $learninglist = Db::query("select * from v_learnings  v,".$subsql." w where  w.student_id=v.student_id and v.learning_paper_result='待考中'");


 //dump($learninglist);
 // return;

        $db_re = Students::alias("students")
        ->field('distinct students.student_idcard_number, students.student_name,w.create_time,
        students.student_phone,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id ')
        ->join('v_learnings w' ,"students.student_id = w.student_id",'LEFT')
        ->where("students.delete_time IS NULL")
          ->where("w.learning_type_id",1)
      ->where('DATE_SUB(CURDATE(), INTERVAL 10 DAY) <= DATE(w.create_time)')    
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
       // ->buildSql();
       ->paginate(session('pagerows','','think'));//返回对象
       // dump($db_re);
       // return;
        $fieldname=[  'student_phone','paper_date','paper__date','create_time'];
       $title = '以下為過去十天新稟學生，共'.$db_re->total().'位。';
       $titlename = ['聯絡電話','理論考期','實習考期','新稟日期'];
         $this->assign('learninglist',$learninglist); 	
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');


    }

    public function read3()
    {   //  理論
        $title = '理論';
        $this->assign("worklog",'3');
     //   $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field(' distinct students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,DATEDIFF(W.learning_paper_date,NOW()) as ts,
        concat(w.learning_paper_date ," [" ,DATEDIFF(W.learning_paper_date,NOW()),"天]" ) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','<',3) 
        ->where('w.learning_paper_result','待考中')
        ->where('w.learning_paper_date >= CURDATE()')  
        ->where('DATE_SUB(CURDATE(), INTERVAL -30 DAY) >= DATE(w.learning_paper_date)')  
   
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象

        $fieldname=['student_phone'
        ,'learning_type','learning_paper_date','learning_paper_result'];
        $title = '未來三十天考駕駛理論學生，共'.$db_re->total().'位。';

   
       $titlename = ['聯絡電話','考试项目','理論考期','結果'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');



    }

    public function read4()
    {   //  理論合格
        $this->assign("worklog",'4');
        $title = '理論合格';
       // $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','<',3)    
        ->where('w.learning_paper_result','合格')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象

        $fieldname=['student_phone','learning_type','learning_paper_date','learning_paper_result'];

       $titlename = ['聯絡電話','考试项目','理論考期','結果'];
       
       $title = '以下為"駕駛理論"合格的學生，共'.$db_re->total().'位。';
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');

    }

    public function read5()
    {   //  理論不合格
        $this->assign("worklog",'5');
        $title = '理論不合格';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','<',3)    
        ->where('w.learning_paper_result','不合格')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象

        $fieldname=['student_phone','learning_type','learning_paper_date','learning_paper_result'];


       $titlename = ['聯絡電話','考试项目','理論考期','結果'];
       $title = '以下為"駕駛理論"不合格的學生，共'.$db_re->total().'位。';
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');

    }

    public function read6()
    {   // 技術
        $this->assign("worklog",'6');
        $title = '技術';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," [" ,DATEDIFF(W.learning_paper_date,NOW()),"天]") as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_result','待考中')
        ->where('w.learning_paper_date >= CURDATE()')  
        ->where('DATE_SUB(CURDATE(), INTERVAL -30 DAY) >= DATE(w.learning_paper_date)')  
        
        //->where('w.learning_paper_result','不合格')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
       // ->buildSql();
        ->paginate(session('pagerows','','think'));//返回对象

      // dump($db_re);
       //return;

        $fieldname=['student_phone','learning_type','learning_paper_date','learning_paper_result'];

       
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','結果'];
       $title='未來三十天考駕駛實習學生，共'.$db_re->total().'位。';
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');

       
    }

    public function read7()
    {   // 技術合格
        $title = '技術合格';
        $this->assign("worklog",'7');
     //   $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_result','合格')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象
        $title = '以下為"駕駛實習"合格的學生，共'.$db_re->total().'位。';
        $fieldname=['student_phone','learning_type','learning_paper_date','learning_paper_result'];

 
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','結果'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');

      
    }

    public function read8()
    {   // 技術不合格
        $this->assign("worklog",'8');
        $title = '技術不合格';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_result','不合格')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象

        $fieldname=['student_phone','learning_type','learning_paper_date','learning_paper_result'];
        $title = '以下為"駕駛實習"不合格的學生，共'.$db_re->total().'位。';
      
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','結果'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');
        
    }

    public function read9()
    {   // 技術未有結果
        $this->assign("worklog",'9');
        $title = '技術未有結果';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_date < CURDATE()')  
        ->where('w.learning_paper_result','待考')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象

        $fieldname=['student_idcard_number','student_name','student_phone'
        ,'car_type','learning_type','learning_paper_date','learning_paper_result'];
        $title = '以下為"駕駛實習"未有結果的學生，共'.$db_re->total().'位。';
  
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','結果'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');
    }

    public function read10()
    {   // 已分配師傅
        $this->assign("worklog",'10');
        $title = '已分配師傅';
       // $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.coach_name,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_result','待考')
        ->where('w.coach_name','>','')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象
        $title = '以下為"駕駛實習"已分配師傅的學生，共'.$db_re->total().'位。';
        $fieldname=['student_phone','learning_type','learning_paper_date','coach_name'];
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','師傅'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');


    }

    public function read11()
    {   // 未分配師傅
        $this->assign("worklog",'11');
        $title = '未分配師傅';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_result','待考')
        ->where('w.coach_name','')
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象

        $fieldname=['student_phone' ,'learning_type','learning_paper_date','learning_paper_result'];
        $title = '以下為"駕駛實習"未分配師傅的學生，共'.$db_re->total().'位。';
       
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','結果'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');
    }

    public function read12()
    {   // 未完成技術付費
        $this->assign("worklog",'12');
      //  $title = '未完成技術付費';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        w.learning_price-w.learning_pay_price as price,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_price>w.learning_pay_price')    
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象
        $title = '以下為"駕駛實習"未完成技術付費的學生，共'.$db_re->total().'位。';
        $fieldname=['student_phone' ,'learning_type','learning_paper_date','price'];

       
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','欠费'];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');
   
    }

    public function read13()
    {   // 已完成技術付費
        $this->assign("worklog",'13');
        $title = '已完成技術付費';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,
        students.student_phone,students.create_time,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        w.learning_pay_price,
        concat(w.learning_paper_date ," " ,left(w.learning_paper_time,5)) as learning_paper_date ,
        w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_price=w.learning_pay_price') 
        ->order('students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象
        $title = '以下為"駕駛實習"已完成技術付費的學生，共'.$db_re->total().'位。';
        $fieldname=['student_phone' ,'learning_type','learning_paper_date','learning_pay_price'];
       
       $titlename = ['聯絡電話','考试项目','駕駛實習考期','交费金额 '];
   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');
    }

    public function read14()
    {   // 師傅
        $this->assign("worklog",'14');
        $title = '師傅';
      //  $subsql=$this->subsqlstring();
        $db_re = Students::alias("students")
        ->field('students.student_idcard_number, students.student_name,w.create_time,
        students.student_phone,students.create_time,w.coach_name,students.student_foreign_surname,students.student_foreign_name,
        w.car_type,students.student_id,w.learning_type,
        w.learning_paper_date ,w.learning_paper_time,w.learning_paper_result
         ')
        ->join('v_learnings w' ,"students.student_id = w.student_id")
        ->where("students.delete_time IS NULL")
        ->where('w.learning_type_id','>',2)    
        ->where('w.learning_paper_result','待考')
        ->where('w.coach_name','>','')
        ->order('w.coach_name,students.student_id,w.car_type_order,w.learning_type_order')
        ->paginate(session('pagerows','','think'));//返回对象
        $title = '以下為師傅分配的學生表。';

        $fieldname=['student_phone','coach_name','learning_paper_date','learning_paper_time','learning_paper_result'];

     
       $titlename = ['聯絡電話','師傅姓名','技術考試日期','技術考試時間','技術考試結果'];   
       $this->assign('listdata',$db_re);
       $this->assign('title',$title);
       $this->assign('titlename',$titlename);
       $this->assign("fieldname",$fieldname);
       return $this->fetch('worklog/read');



    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit()
    {
       
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
         
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function subsqlstring()
    {
        $subsql = Db::table('learnings')->alias('a')
        ->field('c.learning_type,       b.car_type,b.car_type_order,c.learning_type_order,
        a.learning_id,a.car_type_id,a.student_id,a.learning_paper_date,a.learning_pay_price,a.create_time,
        a.learning_paper_time,a.learning_paper_result,a.learning_relate_info,a.learning_valid,
        a.coach_name,a.learning_price,a.learning_status,a.learning_type_id ')
        ->join("car_types b","a.car_type_id =b.car_type_id")
        ->join("learning_types c","a.learning_type_id =c.learning_type_id")
        //->distinct(true)
        ->buildSql();
    
        return $subsql;

    }
    public function delete($id)
    {
 
    }
}
