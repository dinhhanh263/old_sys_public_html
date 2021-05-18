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

$post_cc_request_status = $_POST['cc_request_status'];
$post_shop_request_status = $_POST['shop_request_status'];
header('Content-type: text/plain; charset= UTF-8');
if ((isset($post_cc_request_status) && is_numeric($post_cc_request_status)) || (isset($post_shop_request_status) && is_numeric($post_shop_request_status)) && $_POST['contract_id'] && $_POST['customer_id']) {
    $contract_id = h($_POST['contract_id']);
    $customer_id = h($_POST['customer_id']);
    $shop_id = $authority_shop['id'] ? $authority_shop['id'] : h($_POST['shop_id']);

    $reg_date = date("Y-m-d H:i:s");
    $finance_request_items = Get_Table_Row("request_items"," WHERE (type=1 OR type=2) AND del_flg=0 AND end_flg=0 AND customer_id=".$customer_id." ORDER BY id DESC LIMIT 1");
    // if (($post_cc_request_status || $post_shop_request_status) <> $finance_request_items['status']) {
        // 元依頼事項を終了させる
        // if ($cc_request_items['id']) {
        //     $GLOBALS['mysqldb']->query('UPDATE request_items SET end_flg=1,end_date=now() WHERE id="'.$cc_request_items['id'].'" ') or die('query error'.$GLOBALS['mysqldb']->error);
        // }
        // 経理依頼事項格納
        if ($post_cc_request_status || $post_shop_request_status) {
            if($post_shop_request_status == 1){
                // 店舗依頼事項格納
                $request_items_field = array("type", "customer_id", "contract_id", "shop_id", "status", "reg_date");
                $request_items_data = array(
                    "type" => 2,
                    "customer_id" => $customer_id,
                    "contract_id" => $contract_id,
                    "shop_id" => $shop_id,
                    "status" => $post_shop_request_status,
                    "reg_date" => $reg_date,
                );
            }else{
                // CC依頼事項格納
                $request_items_field = array("type", "customer_id", "contract_id", "shop_id", "status", "reg_date");
                $request_items_data = array(
                    "type" => 1,
                    "customer_id" => $customer_id,
                    "contract_id" => $contract_id,
                    "shop_id" => $shop_id,
                    "status" => $post_cc_request_status,
                    "reg_date" => $reg_date,
                );
            }
                $request_items_id = Input_New_Data2("request_items", $request_items_field, $request_items_data);
                echo $request_items_id;
        // }
    } else {
        echo $finance_request_items['id'];
    }
} else {
    header("Location: /admin/main/");
}
