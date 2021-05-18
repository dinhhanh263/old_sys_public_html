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
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "customer";


// 詳細を取得------------------------------------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
	$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");

	$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['id'])."' order by contract_date desc, id DESC");
	//予約リスト
	$sql = $GLOBALS['mysqldb']->query( "select * from reservation WHERE del_flg = 0 and customer_id = '".addslashes($_POST['id'])."' order by id DESC" ) or die('query error'.$GLOBALS['mysqldb']->error);
	if($sql){
		$i = 1;
		//$rsv_html = '<p>予約履歴:</p>';
		while ( $result = $sql->fetch_assoc() ) {
			$rsv_html .= '<p>予約日時:&nbsp;&nbsp;<a href="../reservation/edit.php?id='.$result['id'].'&shop_id='.$result['shop_id'].'&hope_date='.$result['hope_date'].'"  target="_blank">'.$result['hope_date'].' '.$gTime2[$result['hope_time']].'</a></p>';
			$rsv_html .= '<p>区&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分:&nbsp;&nbsp;'.$gResType4[$result['type']].'</p>';
			if($result['rsv_status'])$rsv_html .= '<p>予約状況:&nbsp;&nbsp;'.$gRsvStatus[$result['rsv_status']].'</p>';
			$rsv_html .= '<p>来店状況:&nbsp;&nbsp;'.$gBookStatus[$result['status']].'</p>';
			$rsv_html .= '<div class="lines-dotted-short"></div>';
		}
	}
}

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist("shop");

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}

?>
