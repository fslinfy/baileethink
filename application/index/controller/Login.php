<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

/**
* Login class
*/
class Login extends Controller
{
	public function index()
	{
		session('login_success',false,'think');
			session('login_username','','think');
			session('group_access','','think');

		return view();
	}

	public function check()
	{
		# code...
		// echo 'login.check()';
		// dump(input());

		
		$username = input('username');
		$password = input('password');
		$sql='select user_name,group_access from users,groups where users.group_id=groups.group_id and  user_account="'.$username .'" and user_password="'.$password.'"';
		$result = Db::query($sql);

		if (!$result){
			//dump($result);
			$this->error('用户帐号不存在或密码错误，登录失败！', url('index\Login'));
            
		}
//dump($result[0]["group_access"]);
//return;
		
		
			session('login_success',true,'think');
			session('login_username',$result[0]["user_name"],'think');
			session('group_access',$result[0]["group_access"],'think');

			$this->success('login success',url('index\index\index'));
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