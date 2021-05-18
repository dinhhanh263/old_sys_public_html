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
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

if(!$_POST['shop_id']) $_POST['shop_id'] = 1;
$table = "rest";

// データの新規登録-----------------------------------------------------------------
if( $_POST['action'] == "rest" ){
	//古い休憩を削除
	$sql = "UPDATE ".$table." SET del_flg = 1";
	$sql .= " WHERE staff_id= ".$_POST['staff_id'] ." AND shop_id= ".$_POST['shop_id'] ." AND hope_date= '".$_POST['hope_date']."'";
	if($_POST['length']==1) $sql .= " and length=2";
	$dRes = $GLOBALS['mysqldb']->query($sql);

	$_POST['reg_date'] = date("Y-m-d H:i:s");
	Input_Data($table);
}	
// データの仮削除
if( $_REQUEST['action'] == "del" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
}

header("Location: ./staff.php?shop_id=".$_REQUEST['shop_id']."&hope_date=".$_REQUEST['hope_date']);
exit();

?>