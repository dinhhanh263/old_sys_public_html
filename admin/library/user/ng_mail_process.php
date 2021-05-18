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

// 新規追加
if( $_POST['mode'] == "ng_process" ){
	if ( $_FILES["upfile"]["size"] === 0 ) {
		$gMsg .=  "<font color='red'>※　アップロードするファイルを指定してください。</font><br>\n";
	}
	$upfile = $_FILES['upfile']['tmp_name'];

	if(file_exists($upfile)){ 
		$contents = file_get_contents ($_FILES['upfile']['tmp_name']);
		$contents = str_replace("\r\n", "\n", $contents); 
		$contents = str_replace("\r", "\n", $contents); 
		$lines = preg_split ('/[\n]+/', $contents);
		foreach($lines as $ng_address){
			$ng_address = trim($ng_address);
			$sql = "UPDATE user SET status=3 WHERE mail = '".addslashes($ng_address)."'";
			$GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
		}
		$gMsg = '処理が完了しました。';
	}
}

?>