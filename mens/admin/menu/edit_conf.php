<?php 
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );

$gMenuPage = 	array( 0 => "代理店管理" , 1 => "広告管理" , 2 => "ユーザー管理" , 3 => "コンテンツ管理" , 4 => "メールマガジン管理" , 5 => "アクセス解析" , 6 => "システム管理" );


// 編集or新規
$table = "menu";
if( $_POST['action'] == "input" ){
	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : Input_Data($table);
	if( $data_ID ){
		$gMsg = '変更が完了しました。<br><br><b><a href="index.php">メニュー管理画面へ</a></b>';
	}else{
		$gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo MANAGE_TITLE; ?></title>
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/active.js"></script>
<link type="text/css" rel="stylesheet" href="../css/base.css">
<link type="text/css" rel="stylesheet" href="../css/top.css">
<link type="text/css" rel="stylesheet" href="../css/mmmain.css">
<link href="../css/def.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/JavaScript">
function check_form(){
	if( confirm( "上記の情報を変更してよろしいですか？" ) ){
		return true;
	}else{
		return false;
	}
}

</script>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="wrap">
<br>
<img src="../img/top01.gif" alt="<?php echo SITE_NAME; ?>" width="1000"><br>
<br><h4 >メニュー情報　編集確認</h4>
<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check_form()">
<table border="0" cellspacing="1" cellpadding="3" width="1000">
<?php if ( $gMsg ) { ?>
<tr>
<td width="30">&nbsp;</td>
<td width="1000"><?php echo($gMsg); ?></td>
</tr>
<?php }else{?>
<!--メニュー start-->
	<tr>
		<td colspan="2" class="td_name">　　▼メニュー情報　変更確認</td>
	</tr>
	<tr>
		<td width="130" class="td_name">メニュー名</td>
		<td width="470" class="td_val"><?php echo $_POST['name'] ?><input name="name" type="hidden" value="<?php echo $_POST['name'] ?>" ></td>
	</tr>
	<tr>
		<td width="130" class="td_name">ファイル名</td>
		<td width="470" class="td_val"><?php echo $_POST['onclick'] ?><input name="onclick" type="hidden" value="<?php echo $_POST['onclick'] ?>" ></td>
	</tr>　
	<tr>
		<td width="130" class="td_name">表示場所</td>
		<td width="470" class="td_val"><?php echo $gMenuPage[$_POST['page']] ?><input name="page" type="hidden" value="<?php echo $_POST['page'] ?>" ></td>
	</tr>
	<tr>
		<td width="130" class="td_name">利用者</td>
		<td width="470" class="td_val"><?php echo $gAuthority[$_POST['authority']] ?><input name="authority" type="hidden" value="<?php echo $_POST['authority'] ?>" ></td>
	</tr>
	<tr>
		<td width="130" class="td_name">表示状態</td>
		<td width="470" class="td_val"><?php echo $gMenuStatus[$_POST['status']] ?><input name="status" type="hidden" value="<?php echo $_POST['status'] ?>" ></td>
	</tr>
	<tr>
		<td width="130" class="td_name">表示順</td>
		<td width="470" class="td_val"><?php echo $_POST['rank'] ?><input name="rank" type="hidden" value="<?php echo $_POST['rank'] ?>" ></td>
	</tr>　
	<tr align="center">
		<td colspan="2" class="td_name"><br>
		<input type="submit" value="　変更確定　">　　
		<input type="button" value="　　戻　る　　" onClick="history.back();">
		<input name="id" type="hidden" value="<?php echo $_POST['id']?>">
		<input name="action" type="hidden" value="input">
		</td>
	</tr>

<!--メニュー end-->
<?php }?>
</table>
</form>
<br><br>
<?php echo $gFooter ?>
</div>
</body>
</html>

