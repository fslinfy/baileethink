<?php 
namespace app\index\controller;


use think\Controller;
/**
* 
*/
class Userinfo extends Controller
{

	protected $beforeActionList=[
		'one',
		'two' => ['except'=>'index'],
		'three' => ['only' => 'index'],
	];

	public function one()
	{
		# code...
		echo "one<hr>";
	}

	public function two()
	{
		# code...
		echo "two<hr>";
	}
	public function three()
	{
		# code...
		echo "three<hr>";
	}


	public function index()
	{
		# code...
		return "userInfo.Index.";
	}

	public function hello()
	{
		# code...
		return 'UserInfo.hello';
	}
	
}

 ?>