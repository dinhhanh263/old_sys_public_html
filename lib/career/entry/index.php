<?php
if(!isset($_SESSION)){
	session_start();
}
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/tag_job_sp.php" );
include_once( "../../lib/career/index.php" );


// 中途採用向け・求人媒体リスト
$today = date("Y-m-d");
$job_media_sql  = $GLOBALS['mysqldb']->query( "select * from job_media WHERE del_flg = 0 AND status=0 AND (start_date = '0000-00-00' or start_date <= '".$today."') AND (end_date = '0000-00-00' or end_date >= '".$today."') ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $job_media_sql->fetch_assoc() ) {
	$job_media_list[$result['id']] = $result['name'];
}
// 店舗リスト
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

	$job_media_other = $job_media_list[1];
	unset($job_media_list[1]);
?>