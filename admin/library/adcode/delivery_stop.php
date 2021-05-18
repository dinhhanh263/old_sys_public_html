<?php 
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

$_POST['id'] = $_POST['id'] ? $_POST['id'] : $_GET['id'];

//広告コード発行
if( $_POST['action'] == "send" && $_POST['id']) {
	//送信
	if( sendMail( $_POST['to_mail'], $_POST['subject'], $_POST['body'],$_POST['from_mail'], $_POST['cc'], $_POST['bcc'] ) ){
	$sql = "UPDATE adcode SET del_flg=1,del_date='".date("Y-m-d H:i:s") . "' WHERE id = " . addslashes($_POST['id']);
		$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
		$send_flg = true;
	}
}

// 詳細を取得
if(  $_POST['id'] != "" ){
	$adcode = Get_Table_Row("adcode"," WHERE id = '".addslashes($_POST['id'])."'");
	$agent = Get_Table_Row("agent"," WHERE id = '".addslashes($adcode['agent_id'])."'");
}

$body =  '
株式会社'.$agent['name'].' '.$agent['tantou'].'様
 
 平素、お世話になっております。
無敵道　広告担当、杉田です。

下記の媒体の配信停止をお願い致します。

【媒体名】'.$adcode['name'].'
      
クライアント名は「'.SITE_NAME.'」です。
どうぞ宜しくお願い致します。

 ';
?>