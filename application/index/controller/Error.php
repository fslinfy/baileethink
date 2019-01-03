<?php

namespace app\index\controller;

use think\Controller;
/**
* 
*/
class Error extends Controller
{
	
	public function index()
	{
		# code...
		$this->redirect('index/index');
	}

	public function _empty()
	{
		# code...
		$this->redirect('index/index');
	}
}

?>