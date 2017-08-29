<?php

ob_start();
session_start();
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
if(!file_exists("config.php")){
	header("Location: install/index.php");
	die();
}
include(DIR_APPLICATION."config.php");
$msg = 'none';
$sql = '';
if(isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['password']) && $_POST['password'] != ''){
	$encoding = 'UTF-8';
	$user = htmlspecialchars(make_safe($_POST['username']), ENT_QUOTES, $encoding);
	$pwd = htmlspecialchars(make_safe($_POST['password']), ENT_QUOTES, $encoding);
	

	if($_POST['ddlLoginType'] == '1'){
		$sql= $con->prepare("SELECT *,b.branch_name FROM tbl_add_admin aa left join tblbranch b on b.branch_id = aa.branch_id WHERE aa.email = :user and aa.password =:pwd");
		$sql ->execute(array('user'=>$user,'pwd'=>$pwd));
	}
	if($_POST['ddlLoginType'] == '2'){
		$sql= $con->prepare("SELECT *,b.branch_name FROM tbl_add_owner o left join tblbranch b on b.branch_id = o.branch_id WHERE o.o_email =:user and o.o_password =:pwd");
		$sql ->execute(array('user'=>$user,'pwd'=>$pwd));
	}
	if($_POST['ddlLoginType'] == '3'){
		$sql= $con->prepare("SELECT *,b.branch_name FROM tbl_add_employee e left join tblbranch b on b.branch_id = e.branch_id WHERE e.e_email =:user and e.e_password =:pwd");
		$sql ->execute(array('user'=>$user,'pwd'=>$pwd));
	}
	if($_POST['ddlLoginType'] == '4'){
		$sql= $con->prepare("SELECT *,b.branch_name FROM tbl_add_rent ad left join tblbranch b on b.branch_id = ad.branch_id WHERE ad.r_email =:user  and ad.r_password =:pwd");
		$sql ->execute(array('user'=>$user,'pwd'=>$pwd));
	}
	if($_POST['ddlLoginType'] == '5'){
		$sql=$con->prepare("SELECT * FROM tblsuper_admin WHERE email =:user and password = :pwd");
		$sql ->execute(array('user'=>$user,'pwd'=>$pwd));

	}
	if($row = $sql->fetch()){
		//here success
		if($_POST['ddlLoginType'] == '5'){
			$arr = array(
				'user_id'		=> $row['user_id'],
				'name'			=> $row['name'],
				'email'			=> $row['email'],
				'password'		=> $row['password'],
				'branch_id'		=> '13',
				'branch_name'	=> 'BlueNet',
				'added_date'	=> $row['added_date']
			);
			$_SESSION['objLogin'] = $arr;
		}
		else{
			$_SESSION['objLogin'] = $row;
		}
		
		$_SESSION['login_type'] = $_POST['ddlLoginType'];
		
		if($_POST['ddlLoginType'] == '1' || $_POST['ddlLoginType'] == '5'){
			header("Location: dashboard.php");
			die();
		}
		else if($_POST['ddlLoginType'] == '2'){
			header("Location: o_dashboard.php");
			die();
		}
		else if($_POST['ddlLoginType'] == '3'){
			header("Location: e_dashboard.php");
			die();
		}
		else if($_POST['ddlLoginType'] == '4'){
			header("Location: t_dashboard.php");
			die();
		}
	}
	else{
		$msg = 'block';
	}
	
}
function make_safe($variable) 
{
   $variable = strip_tags((trim($variable)));
   return $variable; 
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>RENTERS PMS </title>
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<!-- CUSTOM STYLES-->
<link href="assets/css/custom.css" rel="stylesheet" />
<!-- GOOGLE FONTS-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
</head>
<body>
<div class="container"> <br/>
    <br/>
  <div class="row text-center ">
    <div class="col-md-12"><br/>
      <span style="font-size:35px;font-weight:bold;color:red;">RENTERS PMS</span> <p><span style="font-size:18px;"> Property Management System</span> </p>  </div>
  </div>
  
  <div class="row ">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
      <div style="margin-bottom:8px;padding-top:2px;width:100%;height:25px;background:#E52740;color:#fff; display:<?php echo $msg; ?>" align="center">Wrong login information</div>
      <div class="panel panel-default" id="loginBox">
        <div class="panel-heading"> <strong> Enter Login Details </strong> </div>
        <div class="panel-body">
          <form onSubmit="return validationForm();" role="form" id="form" method="post">
            <br />
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
              <input type="text" name="username" id="username" class="form-control" placeholder="Your Email" />
            </div>
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
              <input type="password" name="password" id="password" class="form-control"  placeholder="Your Password" />
            </div>
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-user"  ></i></span>
              <select name="ddlLoginType" onChange="mewhat(this.value);" id="ddlLoginType" class="form-control">
                <option value="">--Select User--</option>
                <option value="1">Manager</option>
                <option value="2">Sub Owner</option>
                <option value="3">Staff</option>
                <option value="4">Tenant</option>
                <option value="5">Admin</option>
              </select>
            </div>
             <div id="x_branch" style="display:none;" class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-plus"  ></i></span>
              <select class="form-control" name="ddlBranch" id="ddlBranch">
              <option value="">--Select Property--</option>
              <?php 
				  	$result_branch = $con->prepare("SELECT * FROM tblbranch order by branch_name ASC");
				  	$result_branch->execute();

					while($row_branch = $result_branch->fetch())
					{?>
                      <option value="<?php echo $row_branch['branch_id'];?>"><?php echo $row_branch['branch_name'];?></option>
              <?php } ?>
            </select>
            </div>
           
            <div class="form-group">
              <label class="checkbox-inline"> </label>
              <span class="pull-right"> <!--<a href="<?php echo WEB_URL;?>forgetpassword.php" >Forgot Password ? </a> </span>--> </div>
            <hr />
            <div align="center">
              <button style="width:100%;" type="submit" id="login" class="btn btn-primary"><i class="fa fa-user"  ></i>&nbsp;Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function validationForm(){
	if($("#username").val() == ''){
		alert("Email Required !!!");
		$("#username").focus();
		return false;
	}
	else if(!validateEmail($("#username").val())){
		alert("Valid Email Required !!!");
		$("#username").focus();
		return false;
	}
	else if($("#password").val() == ''){
		alert("Password Required !!!");
		$("#password").focus();
		return false;
	}
	else if($("#ddlLoginType").val() == ''){
		alert("Select User Type !!!");
		return false;
	}
	else{
		return true;
	}
}
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function mewhat(val){
	if(val != ''){
		if(val == '5'){
			$("#x_branch").show();
		}
		else{
			$("#x_branch").hide();
		}
	}
	else{
		$("#x_branch").hide();
	}
}
</script>
</body>
</html>

