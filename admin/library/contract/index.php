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

if($_GET['gMsg']) {
    $gMsg = "<font color='red' size='-1'>".h($_GET['gMsg'])."</font>";
}
		
$table = "contract";
$customer_id = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
$customer = Get_Table_Row("customer", " WHERE del_flg=0 and id = " . $customer_id);

$dWhere .= " and customer_id=" . $customer_id;

$dSql = "SELECT contract.* FROM " . $table . " JOIN course on contract.course_id = course.id WHERE contract.del_flg = 0" . $dWhere . " ORDER BY course.treatment_type asc, contract.contract_date desc,contract.id desc,contract.course_id asc";
$dRtn3 = $GLOBALS['mysqldb']->query($dSql) or die('query error' . $GLOBALS['mysqldb']->error);

// クーリングオフ歴チェック
$cooling_off_flg = false;
$dSql = "SELECT * FROM contract WHERE customer_id = " . $customer_id . " and del_flg = 0 and status = 2 ";
$dRtn4 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
if ($dRtn4->num_rows >= 1) {
    $cooling_off_flg = true;
}
