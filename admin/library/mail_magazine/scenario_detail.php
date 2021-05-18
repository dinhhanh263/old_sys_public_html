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


$gScenarioList = array(
	'name'		=> array( 'name' => 'シナリオ名',	'type' => 'text', 		'size' => '70', 		'validate' => 'null' ),
	'type'		=> array( 'name' => 'タイプ',		'type' => 'select',		'validate' => 'select', "param" => $gType ) ,
	'genre'		=> array( 'name' => 'ジャンル',	'type' => 'select', 	'validate' => 'select', "param" => $gGenre ) ,
	'total'		=> array( 'name' => '件数',		'type' => 'none',  ) ,
	'date'		=> array( 'name' => '登録日付',	'type' => 'none',  ) ,
);

$_POST['id'] = $_POST['id'] ? $_POST['id'] : $_GET['id'];

// テーブル設定
$table = "mail_scenario_info";

// 編集
if( $_POST['action'] == "edit" ) {
	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
	
	if( $data_ID )$gMsg = '登録が完了しました。<br><br><b><a href="scenario_list.php">登録済みのリストへ</a></b>';
	else		  $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得
if(  $_POST['id'] != "" )$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

?>