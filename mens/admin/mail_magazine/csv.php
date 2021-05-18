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

//テーブル設定
$table = "mail_scenario_data";

// CSVエクスポート

	$dSq4 = "SELECT * FROM " . $table . " WHERE scenario_id=".$_GET['id'];
	$dRtn4 = $GLOBALS['mysqldb']->query( $dSq4);

	$filename = "scenario_list.txt"; 
	header("Content-type:text/txt"); 
	header("Content-Disposition:attachment;filename=".$filename);
	
	echo "メール,名前,配信状態,端末キャリア,日付 \n";
	if ( $dRtn4->num_rows >= 1 ) {
		while ( $list = $dRtn4->fetch_assoc() ) {
			echo  $list['mail']  . ",";
			echo  $list['name']  . ",";
			echo $gStatus[$list['status']] . ",";
			echo $moStatus[$list['mo_agent']] . ",";
			echo  $list['date']  . ",";
			echo "\n";
		}
	}

?>