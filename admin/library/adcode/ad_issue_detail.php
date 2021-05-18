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
if( $_POST['action'] == "send" && $_POST[checkboxName]) {
	//送信
	
	if( sendMail( $_POST['to_mail'], $_POST['subject'], $_POST['body'],$_POST['from_mail'], $_POST['cc'], $_POST['bcc'] ) ){
		//発行済み状態に
		foreach($_POST[checkboxName] as $id=>$vals)
		{
			$sql = "UPDATE adcode SET status=1" . " WHERE id = " . addslashes($id);
			$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
		}
		$send_flg = true;
	}
}

// 詳細を取得
if(  $_POST['id'] != "" )$agent = Get_Table_Row("agent"," WHERE id = '".addslashes($_POST['id'])."'");

$body =  '
株式会社'.$agent['name'].' '.$agent['tantou'].'様
 
平素、お世話になっております。
無敵道　杉田です。

下記のコードを発行させて頂きます。
ご確認の上、ご入稿をお願い致します。

お手数お掛けしますが、どうぞ宜しくお願いします。

●'.SITE_NAME.'
管理画面: '.HOME_URL.'agentadmin/
ID: '.$agent['id'].'
PASS: '.$agent['password'];

$body2 =  '
';

?>