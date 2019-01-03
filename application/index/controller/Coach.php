<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Coachs;
use think\Db;
class Coach extends Controller
{
    protected $beforeActionList=[
        'checklogin',
    ];

    public function checklogin()
    {
        # code...
        // echo 'coach.php checklogin';
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
        $listdata = Coachs::paginate(session('pagerows','','think'));
        $this->assign('listdata',$listdata);
        $this->assign('title','教練資料');
        $this->assign('url','index/coach');
        $this->assign('url1','index/coach');

        $titlename = ['教练编号' ,'中文姓名' ,	'外文姓名' ,	'姓别' ,	'持有', 	'執照編號', 	'狀態' 	];
     
        $fieldname=['coach_code','coach_name','coach_foreign_name','coach_sex','coach_hold','coach_license','coach_status'];
      
        $this->assign('fieldname',$fieldname);
        $this->assign('titlename',$titlename);
        $this->assign('idfield','coach_id');



       return $this->fetch();

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data['id']=0;
        $data['coach_name']='';
        $data['coach_foreign_name']='';
        $data['coach_sex']='男';
        $data['coach_order']='';
        $data['coach_status']=0;
        $data['coach_license']='';
        $data['coach_hold']='';
        $data['coach_phone']='';
        $this->assign('data',$data);
       // $this->assign('formtitle','增加資料');
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
        $coach = new Coachs;
        if($id >= 0){
            $re = Coachs::get($id);
            $re['id']=$id;
        }
        if($re){
            $this->assign('data',$re);

           // $this->assign('formtitle','编辑資料');
            return view();
        }
        else{
            $this->error('修改師傅資料失敗',url('index\coach'));
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
        $data['coach_name']= $data0['coach_name'];
        $data['coach_foreign_name']= $data0['coach_foreign_name'];
        $data['coach_sex']=$data0['coach_sex'];
        $data['coach_order']= $data0['coach_order'];
        $data['coach_license']= $data0['coach_license'];
        $data['coach_hold']= $data0['coach_hold'];
        $data['coach_phone']= $data0['coach_phone'];
        $id= $data0['id'];
        if(isset($data0['coach_status'])){
            $data['coach_status']=1;
        }else{
            $data['coach_status']=0;
        }
        $data['modifiedBy'] = session('login_username','','think');
         if ($id=='0')
         {
            $data['createdBy'] = session('login_username','','think');
           
            $data['create_time'] = date("Y-m-d H:i:sa");
            $student_model = new Coachs($data);
            $re = $student_model->save();
         }
         else
         {
            $result = Coachs::get($id);
            $data['update_time'] = date("Y-m-d H:i:sa");
             if    ( $data0['savetype'] =='del'){
                     $re = $result->delete();
             }
             else
             {
                
                $re = $result->save($data);
             }
         }      
         $this->redirect(url('index\coach\index'));  
    }

}
