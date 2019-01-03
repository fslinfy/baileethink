<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Questions;

class Question extends Controller
{

    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
        # code...
        // echo 'question.php checklogin';
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
        //$listdata = Questions::order('question_title_number', 'desc')->paginate(session('pagerows','','think')) ;

        $listdata = Questions::where('question_new', '=',0)
        ->order('question_title_number', 'asc')
        ->paginate(session('pagerows','','think')) ;
        $this->assign('newfive','0');
        $this->assign('listdata',$listdata);
        $this->assign('title','技術試題');
        $this->assign('url','index/question');
        session('newfive','0','think');
        $this->assign('url1','index/question');
        $titlename = ['題目編號','題目','類型','冊數','主要題目'];
      
        $fieldname=['question_title_number','question_title','question_type',
        'question_book_number','question_main_title'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','question_id');



       return $this->fetch();

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

   
    
       $data['question_title_number']='';
       $data['question_title']= '';
       $data['question_type']= '';
       $data['question_book_number']= 1;
       $data['question_main_title']= '';


       $data['id']=0;
   
        $this->assign('data',  $data);
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
       
  
        $request=request();
        $str=$request->param('newfive');
        if ($str!='5'){
            $str='0';
        }
        $question = new Questions;
        if($id >= 0){
            $re = Questions::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);
            $this->assign('newfive', $str);
       
            return view('edit');
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
     
        $id= $data0['id'];
     
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['question_new']= session('newfive','','think');
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            dump($data);
            $student_model = new Questions($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Questions::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\question\index'));
        //
        $data = input('post.');
        $data['modifiedBy'] = session('login_username','','think');
        $question = Questions::get($id);
        //echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        //$newfive $question['question_new'];
        if ($question['question_new']=='5'){
            $url="index\\question\\newfive";
        }else{
            $url="index\\question\\index";
        }
        
        $re = $question->save($data);
        $this->redirect(url($url)); 
        
    }

  



    public function newfive(){

        $listdata = Questions::where('question_new', '=',5)
        ->order('question_title_number', 'asc')
        ->paginate(session('pagerows','','think')) ;
        session('newfive','5','think');
        $this->assign('listdata',$listdata);
        $this->assign('title','新五冊試題');
        $this->assign('newfive','5');
        $this->assign('url','index/question');
        $this->assign('url1','index/question');

    
       

        $titlename = ['題目編號','題目',	'類型','冊數','主要題目'];
      
        $fieldname=['question_title_number','question_title','question_type',
        'question_book_number','question_main_title'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','question_id');



       return $this->fetch('index');

    }
}
