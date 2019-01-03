<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    public function index()
    {
      //  dump(request()->url('index\login\index'));
     //   return;
      
        if (!session('login_success','','think')){
            $this->redirect(url('index\login\index'));
        }
        
        $s = Db::name('settings')
            ->where('setting_id', 1)
           ->value('setting_page_contain');

        session('pagerows',$s,'think');
       
       // session('login_username','admin','think');
       // session('login_success',1,'think');
        $request = Request::instance();
      


        return view('index1');
    }
    public function menu()
	{
        $s = Db::name('settings')
        ->where('setting_id', 1)
       ->value('setting_page_contain');

    session('pagerows',$s,'think');
		return view('index1');
	}
}