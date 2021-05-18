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

$table = "bank";

// 詳細を取得----------------------------------------------------------------------------
if($_REQUEST['customer_id']) $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['customer_id'])."'");
else 						 $customer = array();

if($customer['smartpit_id']) $smartpit_no = Get_Table_Col("smartpit","smartpit_no"," WHERE del_flg=0 AND id = '".addslashes($customer['smartpit_id'])."'");
else 						 $smartpit_no = "";

if($customer['id']) 		 $virtual_bank = Get_Table_Row("virtual_bank"," WHERE del_flg=0 AND customer_id = '".addslashes($customer['id'])."'");
else 						 $virtual_bank = "";
