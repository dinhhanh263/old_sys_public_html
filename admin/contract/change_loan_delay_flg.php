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

$post_loan_delay_flg = $_POST['loan_delay_flg'];
header('Content-type: text/plain; charset= UTF-8');
if (isset($post_loan_delay_flg) && is_numeric($post_loan_delay_flg) && $_POST['contract_id']) {
    $contract_id = h($_POST['contract_id']);
    $_POST['edit_date'] = date('Y-m-d H:i:s');

    $contract_field = array("loan_delay_flg","edit_date");
    $contract_id = Update_Data("contract", $contract_field, $contract_id); 
    echo $contract_id;
} else {
    header("Location: /admin/main/");
}
