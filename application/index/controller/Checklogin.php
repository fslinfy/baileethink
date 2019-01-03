<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Checklogin extends CI_Controller {
  public function index() 
  {  
     $link = mysqli_connect('gz-cdb-p2hbsmqa.sql.tencentcdb.com:63689', 
     'root','lfy670313','wms'); 
     session_start();
     $act = $_GET['act'];
   //  if (!$act) {
	 //     $act = $_POST['act'];
   //  }
     $act = strtolower($act);
     $retval = '';

    switch($act) {
	    case 'vipuseractive' :
		    $retval = self::vipuseractive($link);
		    break;
 
	    default :
		    $retval = self::sysuserlogin($link);
		    break;
    }
    mysqli_close($link);	
    echo $retval;
  }


private static function sysuserlogin($link) {

	$p_khid = (int)$_GET['khid'];
	$username = $_GET['username'];
  $openid = $_GET['openid']; 
	$userid = (int)$username;
	$wxname = $_GET['nickName'];
	$userpsw = base64_encode($_GET['password']);
    if ($p_khid>0){
  $sqlstr = "select u.*,'' as lidstring,0 as khsystem,customer.C_name as khmc,customer.C_shortname as khjc from khusers u,customer  where customer.C_id=u.khid and u.active=1  ";
    }else{
      $sqlstr = "select u.*,t.new,t.edit,t.del,t.sh,t.system,t.cwsh,0 as khsystem,'' as khmc,'' as khjc from users u,usertype t  where u.typeid=t.typeid and u.active=1  ";

    }
  if ($userid>0)
  {
		$sqlstr .= " and  u.userid=" . $userid;
  }
  else
  {
	  $sqlstr .= "and u.wxautologin=1 and  u.openid='" . $openid."'";
  }


//	$sqlstr .= " and  u.password='" . $userpsw . "'";
	//return $sqlstr;
	$id = "0";
	$name = "";
	$lidstring = "";
	$sh = 0;
	$cwsh = 0;
	$edit = 0;
	$smsactive = 0;
  $wxlogin = 0;
  $password="";
  $wx_name ='';
	$del = 0;
	$lastdel = 0;
	$new = 0;
	$khsystem = 0;
	$E_name = "";
	$khmc = "";
  $khjc = "";
  $locked=0;
  $trylogin=0;
	$sqlstr1 = "";
  $lidstring= "";
	$query = mysqli_query($link, $sqlstr);

	if ($query) {
		while ($row = mysqli_fetch_array($query)) {
			$id = (int)$row['userid'];
			$name = $row['username'];
			$del = $row['del'];
			$lastdel = $row['lastdel'];
			$sh = $row['sh'];
			$cwsh = $row['cwsh'];
			$edit = $row['edit'];
			$khmc = $row['khmc'];
      $khjc = $row['khjc'];
			$khid =$p_khid;
      $wx_name = $row['wxname'];
      $wxlogin = $row['wxautologin'];
			$smsactive = $row['smsactive'];
      $locked =(int)$row['locked'];
      $trylogin =(int)$row['trylogin'];
			$khsystem = $row['khsystem'];
			$password = $row['password'];
			$new = $row['new'];
      $lidstring = $row['lidstring'];
			break;
		}
	}
	if ($id < 1) {
		$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('没有此操作用户！ '));
		return urldecode(json_encode($arr));
	}

  if ($smsactive == 0) {
		$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('请用户先激活再进行登录操作！！！ '));
		return urldecode(json_encode($arr));
  }
  
  if ($locked ==1) {
		$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('用户帐号被锁，请管理员先解锁再进行登录操作！！！ '));
		return urldecode(json_encode($arr));
  }
  


	if (  $password!=$userpsw ) {
    if ($p_khid>0){
    if ($trylogin<2){
      $sqlstr1 = "update khusers  set trylogin=trylogin+1 where userid=" . $id;
    }else{
      $sqlstr1 = "update khusers  set trylogin=trylogin+1,locked=1 where userid=" . $id; 
    }
    }else{
   if ($trylogin<2){
      $sqlstr1 = "update users  set trylogin=trylogin+1 where userid=" . $id;
    }else{
      $sqlstr1 = "update users  set trylogin=trylogin+1,locked=1 where userid=" . $id; 
    }

    }


    mysqli_query($link,$sqlstr1);
		$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('用户第'.($trylogin+1).'次输入密码错误，登录失败！！！ '));
		return urldecode(json_encode($arr));
	}



	$arr['success'] = true;
	$arr['data'] = array('userid' => $id, 'username' => urlencode($name), 'lidstring' => $lidstring, 'sh' => $sh, 'cwsh' =>    $cwsh, 'edit' => $edit, 'khid' => $khid, 'khmc' => $khmc, 'khjc' => $khjc,'del' => $del, 'lastdel' => $lastdel, 'new' => $new,'wxlogin' => $wxlogin,'wxname' => $wx_name, 'khsystem' => $khsystem);

	$sqlstr1 = "update khusers ";
	$sqlstr1 .= " set logincount=logincount+1,lastlogin=now(),locked=0,loginguid='" . $wxname . "' ,trylogin=0 where userid=" . $id;

	$query = mysqli_query($link,$sqlstr1);

	return urldecode(json_encode($arr));

}

private static function vipuseractive($link) {
	



	$userid = (int)$_GET['userid'];
	$khid = (int)$_GET['khid'];
	
	$psw = $_GET['password'];
	$wxname = $_GET['nickName'];
  $openid = $_GET['openid'];
	$wxlogin = $_GET['wxlogin'];

	$arr = array();
	if (($userid == 0) || (!$psw)) {
	 	$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('提交数据内容出错！ '));
		return urldecode(json_encode($arr));
	}

	$userpsw = base64_encode($psw);
	$sqlstr = "";
  if ($khid>0){
	$sqlstr = "update khusers set locked=0,trylogin=0,smsactive=1, password='" . $userpsw . "',wxname='" . $wxname . "',openid='" . $openid . "', wxautologin=" . $wxlogin . " where userid=" . $userid;
  }else
  {

$sqlstr = "update users set locked=0,trylogin=0,smsactive=1, password='" . $userpsw . "',wxname='" . $wxname . "',openid='" . $openid . "', wxautologin=" . $wxlogin . " where userid=" . $userid;

  }



	$query = mysqli_query($link,$sqlstr);

	if (mysqli_errno($link) > 0) {
		$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('用户激活失败！！！ ') . $sqlstr);
		return urldecode(json_encode($arr));
	}
  if ($khid>0){ 
	$sqlstr = "select u.*,'' as lidstring,0 as khsystem,customer.c_name as khmc from khusers u,customer  where customer.c_id=u.khid and  u.active=1 ";
  }else{

      $sqlstr = "select u.*,t.new,t.edit,t.del,t.sh,t.system,t.cwsh,0 as khsystem,'' as khmc,'' as khjc from users u,usertype t  where u.typeid=t.typeid and u.active=1  ";


  }

  



	$sqlstr .= " and  u.userid=" . $userid;
	$id = "0";
	$name = "";
	$lidstring = "";
	$sh = 0;
	$cwsh = 0;
	$edit = 0;
	$del = 0;
	$lastdel = 0;
	$new = 0;
	$khsystem = 0;
	$E_name = "";
	$khmc = "";
	$sqlstr1 = "";
	$query = mysqli_query($link,$sqlstr);
	if ($query) {
		while ($row = mysqli_fetch_array($query)) {
			$id = $row['userid'];
			$name = $row['username'];
			$del = $row['del'];
			$lastdel = $row['lastdel'];
			$sh = $row['sh'];
			$khmc = $row['khmc'];
			$cwsh = $row['cwsh'];
			$edit = $row['edit'];
			$khsystem = $row['khsystem'];
			$lidstring = $row['lidstring'];
			$new = $row['new'];
			break;
		}

		$arr['success'] = true;
		$arr['data'] = array('userid' => $id, 'username' => urlencode($name), 'lidstring' => $lidstring, 'sh' => $sh, 'cwsh' => $cwsh, 'edit' => $edit, 'del' => $del, 'lastdel' => $lastdel, 'new' => $new, 'khmc' => $khmc, 'khsystem' => $khsystem, 'khid' => $khid);
	} else {
		$arr['success'] = true;
		$arr['data'] = array('userid' => 0, 'username' => urlencode('用户激活失败！'));
	}

	return urldecode(json_encode($arr));
}
}