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

$today = date("Y-m-d");

// 編集----------------------------------------------------------------------------
if ($_POST['action'] == "edit") {
    $reservation_contract_id = $_POST['reservation_contract_id'];

    for ($i = 0; $i < count($reservation_contract_id); $i++) {
        $contract = Get_Table_Row("contract", " WHERE del_flg=0 AND id = " . addslashes($reservation_contract_id[$i]) . " AND customer_id =" . addslashes($_POST['customer_id']));
        $GLOBALS['mysqldb']->query('UPDATE reservation SET contract_id="' . $contract['id'] . '", course_id ="' . $contract['course_id'] . '", edit_date = now() WHERE id=' . $_POST['reservation_id'][$i]) or die('query error' . $GLOBALS['mysqldb']->error);
    }

}

if(is_numeric($_GET['customer_id'])) {
    //未来日のトリートメン情報取得
    $dSql = "select r.id, r.shop_id, s.name, r.contract_id, r.hope_date,r.hope_time, r.reg_date, r.course_id, c.name as course_name from reservation r join shop s on r.shop_id = s.id join course c on r.course_id = c.id WHERE r.del_flg = 0 and r.type = 2 and r.sales_id=0 and r.customer_id = '" . addslashes($_GET['customer_id']) . "' and r.hope_date >='" .$today."' order by r.hope_date, r.id desc";
    $dRtn = $GLOBALS['mysqldb']->query($dSql) or die('query error' . $GLOBALS['mysqldb']->error); 
    
    $old_contract = Get_Table_Row("contract", " WHERE del_flg=0 AND id = " . addslashes($_GET['old_contract_id']) . " AND customer_id =" . addslashes($_GET['customer_id']));
    $new_contract = Get_Table_Row("contract", " WHERE del_flg=0 AND id = " . addslashes($_GET['new_contract_id']) . " AND customer_id =" . addslashes($_GET['customer_id']));
    $customer = Get_Table_Row("customer", " WHERE del_flg=0 AND id =" . addslashes($_GET['customer_id']));

    $old_course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . addslashes($old_contract['course_id']) . "'");
    $new_course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . addslashes($new_contract['course_id']) . "'");
    $course_list[$old_contract['id']] = $old_course['name'];
    $course_list[$new_contract['id']] = $new_course['name'];
}



