<?php 
session_start();
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
include_once( "../../lib/tag_sp.php" );
include_once( "../../lib/form_complete_common.php" );

// 生年月日から年齢を計算する
$now = date('Ymd');
$birthday = str_replace("/", "", $_POST['birthday']);
if($birthday && $birthday<>"0000-00-00")$age = floor(($now-$birthday)/10000);

// 20歳未満なら親権者同意書のリンクを表示する
if(intval($age) < 20){
	$msg_younger = '<p><a class="result" href="../minor/index.html" target="_blank">未成年の方は、ご来店前にこちらもご確認ください。</a></p>';
}

?>
