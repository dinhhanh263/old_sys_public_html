<?php
header("Content-type: text/plain; charset=UTF-8");

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
include_once( "../../../lib/auth.php" );

$pdf_param6 = urldecode($_SERVER["REQUEST_URI"]);
$pdf_param6 = strstr($pdf_param6, '?');
$extension_flg = $_GET['extension_flg'];//保証期間延長未・済判定
$start_date = $_GET['p_start'];//契約開始日
$end_date = $_GET['p_end'];//保証終了日
$contract_period = date('Y年 n月 j日 ', strtotime($start_date))."  ～  ".date('Y年 n月 j日 ', strtotime($end_date));//契約期間
if($extension_flg === "1"){//保証期間延長後
  $p_end = date('Y年 n月 j日 ', strtotime($end_date));//保証期間終了日
}else{
  $p_end = date('Y年 n月 j日 ', strtotime($end_date));//保証期間終了日
  $post_end_date = date("Y-n-j",strtotime($end_date . "+2 year"));
  $new_end_date = date("Y年 n月 j日",strtotime($end_date . "+2 year"));//延長後保証期間終了日
  $period = date('Y年 n月 j日 ', strtotime($end_date.' +1 day'))."  ～  ".$new_end_date;//保証期間延長期間
}

$table = "contract";

if($_POST['action'] === 'edit'){
  $customer_id = $_POST['customer_id'];
  $_POST['extension_edit_date'] = date("Y-m-d");
  $_POST['edit_date'] = date("Y-m-d H:i:s");
  $pdf_param6 = urldecode($_SERVER["REQUEST_URI"]);
  $pdf_param6 = strstr($pdf_param6, '?');
  $pdf_param6.="&extension_edit_date=".$_POST['extension_edit_date'];
  $fileds= array("end_date","edit_date","extension_flg","extension_edit_date");
  $target = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($customer_id)."' order by contract_date desc, id DESC");
  $target['id'] = Update_Data($table,$fileds,$target['id']);
  echo $pdf_param6;
}
?>