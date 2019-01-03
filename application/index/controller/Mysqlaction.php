<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

/**
* Login class
*/
class Mysqlaction extends Controller
{
	public function index()
	{   
		# code...
		 echo 'index login.index() <br>';
		 echo request()->url();
		 echo request()->param(0);
		//return view();
	}
	public function getreceipttypeprice()
    {
        
	//	$id=request()->param(0);
	
		$sqlstring='SELECT receipt_type_price, receipt_type_name FROM receipt_types where receipt_type_id='.$_GET['id'];
		//	echo $_GET['id'];
		//return;
		$listdata = Db::query($sqlstring);
		//s.value('receipt_type_price');
		//echo $listdata;
		//dump($listdata);
	   //$this->ajaxReturn (json_encode($listdata ),'JSON');
	   echo json_encode($listdata );
	}

		public function getlearning()
    {
        
		$id=$_GET['id'];
	
		$sqlstring='SELECT a.*,b.* FROM students a,v_learnings b where a.student_id=b.student_id and b.learning_id='.$id;
	
		$listdata = Db::query($sqlstring);
		
	     echo json_encode($listdata );
	}
	public function getpoplist()
    {
        

        $listdata = Db::query('SELECT * FROM poplist');
	   //dump($listdata);
	   //$this->ajaxReturn (json_encode($listdata ),'JSON');
	   echo json_encode($listdata );
	}
	public function check()
	{
		# code...
		// echo 'login.check()';
		// dump(input());
		$username = input('username');
		$password = input('password');
		
		if ($username == 'admin' && $password == 'admin'){
			session('login_success',true,'think');
			session('login_username',$username,'think');
			$this->success('login success',url('/index'));
		} else {
			$this->error('login error', url('index\Login'));
		}
	}

	public function _empty()
	{
		# code...
		return $this->redirect('index/login');
	}

	public function logout()
	{
		# code...
		$_SESSION = array();
		setcookie('PHPSESSID','',time()-3600,'/');
		// session_destroy();
		return $this->redirect('index\login');
	}

}


?>