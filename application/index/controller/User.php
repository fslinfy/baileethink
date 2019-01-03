<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Users;
use think\Db;

class User extends Controller
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
               /* $request = request();
                $page=$request->param('page')  ;
                if (!$page){
                    $page=1;
                }
            



                $sql='SELECT 
                users.user_name,
                u.* 
              FROM
                users,
                 users u 
              WHERE u.user_id = users.user_id 
              ORDER BY users.user_order ,u.user_account '   ;*/


                   $listdata = Users::alias('u')
                  ->field('u.*,groups.group_name')
                  ->join("groups","u.group_id = groups.group_id")
                  ->order('groups.group_order ,u.user_account')
                  //->select();
                  ->paginate(session('pagerows','','think'));//返回对象

                  //dump( $listdata);
                 // return;
              /*  $aa=  Db::table('users')
                  ->where('user_status',1)
                  ->select();//返回数组*/
         
                   
                   $this->assign('listdata',$listdata);
                   $this->assign('title','用戶管理');
                   $this->assign('url','index/user');

                   $this->assign('url1','index/user');
 
                   $titlename = ['用户組別','登錄賬號',	'用戶姓名','聯絡電話','状态'];
      
                   $fieldname=['group_name','user_account','user_name','user_phone','user_status'];
                 
                   $this->assign('fieldname',$fieldname);
                   $this->assign('titlename',$titlename);
                   $this->assign('idfield','user_id');
                   

                  return $this->fetch();


    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $poplist = Db::query('select group_id,group_name from groups order by group_order ');
        $this->assign('poplist',$poplist);


        $data['user_name']='';
        $data['user_account']= '';
        $data['user_phone']= '';
        $data['user_password']="";
        $data['group_id']=0;
        $data['id']=0;
      
        $data['user_status']=1;
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
        // echo 'User.php edit';



        $User = new Users;
        if($id >= 0){
            $re = Users::get($id);
            $re['id']=$id;
        }
        if($re){
            $poplist = Db::query('select group_id,group_name from groups order by group_order ');
            $this->assign('poplist',$poplist);
            $this->assign('data',$re);
            return view();
        }
        else{
            $this->error('修改用户失敗',url('index\user'));
        }
    }
    public function editpassword()
    {
        //
        // echo 'User.php edit';



    
            return view('editpassword');
     
    }
    public function updatepassword()
    {
        $data0 = input('post.');
       
        $user_id=4;
        $result = Users::get($user_id);
      if  ($data0['user_password0']!== $result->user_password)
      {
        $this->error('用户原密码验证失敗！',url('index\user\editpassword'));
      }
      $data['user_password']= $data0['user_password1'];
      $re = $result->save($data);

      $this->success('用户密码修改成功，请用新密码登录！',url('index\login'));


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
     
        $data['user_name']= $data0['user_name'];
        $data['user_account']= $data0['user_account'];
        $data['user_phone']= $data0['user_phone'];
        $data['user_password']= $data0['user_password'];
        $data['group_id']= $data0['group_id'];
           
        if(isset($data0['user_status'])){
            $data['user_status']=1;
        }else{
            $data['user_status']=0;
        }
        $id= $data0['id'];
     
        $data['update_time'] = date("Y-m-d H:i:sa");
        $data['modifiedBy'] = session('login_username','','think');
        
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
         
            $data['create_time'] = date("Y-m-d H:i:sa");
        
            $student_model = new Users($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Users::get($id);
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\user\index')); 
    
    }

    
}
