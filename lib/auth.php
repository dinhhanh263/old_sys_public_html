<?php
//セッション使ってると戻るボタンで戻った時にフォームの内容が消えてしまう問題
//session_cache_limiter('none');

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$_POST = $_REQUEST;
unset($_POST['ARRAffinity']);
unset($_POST['ARRAffinitySameSite']);

//権限分け表示-----------------------------------------------------
$authority = Get_Table_Row("authority"," WHERE login_id = '".addslashes($_SESSION['user_id'])."' and password='".addslashes($_SESSION['pw'])."'");
$authority_level = $authority['authority'] ;

if($authority_level>=7 && $authority['staff_id']){
	$authority_staff = Get_Table_Row("staff"," WHERE id = '".addslashes($authority['staff_id'])."'");
	$authority_shop = Get_Table_Row("shop"," WHERE id = '".addslashes($authority_staff['shop_id'])."'");
	if(!$_POST['shop_id'] && !$_POST['customer_id'] )$_POST['shop_id'] = $authority_shop['id'];//他店舗への予約変更配慮

}
//elseif(!$_POST['shop_id']) $_POST['shop_id'] = 1;

//不正アクセス-----------------------------------------------------
if(!$authority['id'] || empty($_SESSION)){
    header("Location: ../");
    exit();
}


?>