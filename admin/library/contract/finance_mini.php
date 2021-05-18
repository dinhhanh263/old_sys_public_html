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

$table = "request_items";


// 詳細を取得------------------------------------------------------------------------

if( $_POST['contract_id'] != "" )  {
	// 店舗アカウントログイン時の表示
	if ($authority_level==17) {
		$dWhere .= " AND type = 2";
	}

	$sql = $GLOBALS['mysqldb']->query("select * from ".$table." WHERE del_flg = 0 and contract_id = '" . addslashes($_POST['contract_id']) . "'".$dWhere." order by id DESC") or die('query error' . $GLOBALS['mysqldb']->error);
	$contract = Get_Table_Row("contract", " WHERE del_flg=0 and id = '" . addslashes($_POST['contract_id']) . "';");


	if($sql){
		$i = 1;
		while ( $result = $sql->fetch_assoc() ) {
			$rsv_html .= '<p>依頼事項:&nbsp;&nbsp;<a href="../reservation/cc_request.php?request_id='.$result['id'].'" target="_blank">'.$gRequest[$result['status']].'</a></p>';
			$rsv_html .= '<p>依&nbsp;&nbsp;頼&nbsp;&nbsp;日:&nbsp;&nbsp;'.substr($result['reg_date'],0,10).'</p>';
			$rsv_html .= '<p>ステータス:&nbsp;&nbsp;'.$gLoanStatus[$contract['loan_status']].'</p>';

			$rsv_html .= '<div class="lines-dotted-short"></div>';
		}
	}
}


?>
