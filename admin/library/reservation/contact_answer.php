<?php
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "contact_answer";
$html = "";
// 詳細を取得------------------------------------------------------------------------
if( $_REQUEST['no'] != "" )  {
	// リスト
	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0 and no = '".addslashes($_REQUEST['no'])."' order by id DESC" ) or die('query error'.$GLOBALS['mysqldb']->error);
	if($sql){
		$i = 1;
		//$html = '<p>予約履歴:</p>';
		while ( $result = $sql->fetch_assoc() ) {
			$html .= '<p>回答日時:&nbsp;&nbsp;'.$result['reg_date'].'</p>';
			$html .= '<p>Q:&nbsp;&nbsp;'.nl2br($result['content']).'</p>';
			$html .= '<p>A:&nbsp;&nbsp;'.nl2br($result['answer']).'</p>';
			$html .= '<div class="lines-dotted-short"></div>';
		}
	}
}
?>
