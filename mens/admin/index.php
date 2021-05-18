<?php include_once("./library/index.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO</title>
<link rel="shortcut icon" href="./images/favicon.ico" />
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="default" />
<!-- <link rel="shortcut icon" href="../common/icon/favicon.ico" /> -->
<!--  jquery core -->
<script src="js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>

<!-- Custom jquery scripts -->
<script src="js/jquery/custom_jquery.js" type="text/javascript"></script>

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body id="login-bg">

<!-- Start: login-holder -->
<div id="login-holder">

	<!-- start logo -->
	<div id="logo-login">
		<a href=""><img src="images/shared/logo.png" height="40" alt="" /></a>
	</div>
	<!-- end logo -->

	<div class="clear"></div>

	<!--  start loginbox ................................................................................. -->
	<div id="loginbox">

	<!--  start login-inner -->
	<div id="login-inner">
		<div style="line-height:30px;"><?php echo $err_msg;?></div>
	  	<form action="" method="post">
	  	<input type="hidden" name="mode" value="login" />
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>ユーザ名</th>
			<td><input type="text" name="login_id" value="<?php echo $_POST['login_id'];?>" class="login-inp" autocapitalize="off" /></td>
		</tr>
		<tr>
			<th>パスワード</th>
			<td><input type="password" name="login_pw" value="<?php echo $_POST['login_pw'];?>"  onfocus="this.value=''" class="login-inp"  /></td>
		</tr>
		<tr>
			<th></th>
			<td valign="top"><!--<input type="checkbox" class="checkbox-size" id="login-check" /><label for="login-check">Remember me</label>--></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" class="submit-login"  /></td>
		</tr>
		</table>
	  </form>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
	<a href="" class="forgot-pwd">Forgot Password?</a>
 </div>
 <!--  end loginbox -->

	<!--  start forgotbox ................................................................................... -->
	<div id="forgotbox">
	  <form action="" method="post">
		<div id="forgotbox-text">パスワードを再設定するには、ログインに使用しているメール アドレスを入力してください。</div>
		<!--  start forgot-inner -->
		<div id="forgot-inner">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>メールアドレス:</th>
			<td><input type="text" value=""   class="login-inp" /></td>
		</tr>
		<tr>
			<th> </th>
			<td><input type="submit" class="submit-login"  /></td>
		</tr>
		</table>
		</div>
		<!--  end forgot-inner -->
		<div class="clear"></div>
		<a href="" class="back-login">Back to login</a>
	  </form>
	</div>
	<!--  end forgotbox -->

</div>
<!-- End: login-holder -->
</body>
</html>