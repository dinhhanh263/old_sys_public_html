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
include_once( "../../lib/auth.php" );

$table = "shift";

$_POST['shift_date']=$_POST['shift_date'] ? $_POST['shift_date'] : date("Y-m-d");
$_POST['shift_date2']=$_POST['shift_date2'] ? $_POST['shift_date2'] : ($_POST['shift_date'] ? $_POST['shift_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['shift_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['shift_date']." +1day"));

// 検索条件の設定------------------------------------------------------------------------
$dWhere = "";
$dWhere .= " AND  sales.pay_date>='".$_POST['pay_date']."'";
$dWhere .= " AND  sales.pay_date<='".$_POST['pay_date2']."'";
if($_POST['shop_id']) $dWhere .= " AND  sales.shop_id='".$_POST['shop_id'] ."'";

// データの取得------------------------------------------------------------------------
$dSql  = "SELECT sales.*,customer.no as no,customer.name as name,customer.name_kana as name_kana,customer.tel as tel ";
$dSql .= "FROM " . $table . ",customer WHERE sales.customer_id=customer.id AND customer.del_flg=0 AND sales.del_flg = 0".$dWhere;
$dSql .= " ORDER BY sales.pay_date,sales.reg_date ";//sales.type desc:プラン変更がの残金
//
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

while ( $result = $dRtn3->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}
?>