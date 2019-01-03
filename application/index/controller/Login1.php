<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

/**
* Login class
*/
class Login1 extends Controller
{
	public function index()
	{
		# code...
		 echo 'index login.index() <br>';
		 echo request()->url();
		//return view();
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