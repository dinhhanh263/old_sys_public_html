<?php 
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
include_once( "./lib/tag.php" );

session_start();
//$sess = "/home/hoge/tmp/sess_" . session_id();
//chmod($sess, 0644);
if(isset($_GET['adcode']))  $_SESSION['MENS_AD_CODE'] = Get_Table_Col("adcode","id"," where adcode='{$_GET['adcode']}'");

/*//スマートフォン用ページに切り替え
if( !strstr( $_SERVER['SCRIPT_FILENAME'],"sp") && ($mo_agent==1 || $mo_agent==3)) {
		header("Location: /edsp/");
		exit();
}else{*/
	//トップページ集計
	IncrementAccessLog(date('Y-m-d'), 1, $mo_agent, $_SESSION['MENS_AD_CODE']);
	//解析用
	if($page_id)IncrementAccessLog(date('Y-m-d'), $page_id, $mo_agent,$_SESSION['MENS_AD_CODE']); 
//}

//$hit_list = Get_Result_Sql_Array("select * from hit WHERE status=1 and img_mo<>'' order by id DESC limit 6");
//$prospect   = Get_Table_Row("prospect"," WHERE status=1 order by id DESC limit 1");
?>
