<?php
namespace app\index\controller;

use think\Url;

class Blog {
    public function index(){
    	echo 'blog.index()';
    }
    
    public function read($id){
    	echo 'blog.read('.$id.')';
    }    
    
    public function edit($id){
    	echo 'blog.edit('.$id.')';
    }    

    public function geta()
    {
    	# code...
    	echo 'blog.geta()';
    }

    public function urldump(){
    	echo 'blog.urldump';
    	dump(Url::build('index/index/index'));
    	dump(url('index/index/course'));
    	dump(url('index/index/course/id/10'));
    	dump(url('index/index/course','id=5'));
    	dump(url('index/blog/read',['id'=>5,'name'=>'thinkphp']));

    	Url::root('/index.php');
    	dump(url('index/blog/read#anchor@blog','id=5'));
    }
}


?>