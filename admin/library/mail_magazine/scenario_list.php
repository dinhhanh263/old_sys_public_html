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

// データの取得
$table = "mail_scenario_info";
				
// データの変更
if( $_POST['action'] == "update" && $_POST['id']) {
	$GLOBALS['mysqldb']->query("update {$table} set name='{$_POST['data_name']}',type='{$_POST['type']}',genre='{$_POST['genre']}' where id='{$_POST['id']}'") or die('query error'.$GLOBALS['mysqldb']->error);
	$_POST['data_name'] = "";
}

// データの削除
if( $_POST['mode'] == "delete" && $_POST['id']){
	 Delete_Table_Row("mail_scenario_data","scenario_id",$_POST['id']) ;
	 Delete_Table_Row($table,"id",$_POST['id']);
	 $gMsg = "※ ".$_POST['data_name'].' データの削除が完了しました。';
}

// 表示ページ設定
$dStart = 0;
$dLine_Max = $_POST['line_max'] ? $_POST['line_max'] : 20;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定
$dWhere = " where id>=0 ";
if( $_POST['data_name'] )$dWhere .= " and  name LIKE '%".addslashes( $_POST['data_name'] )."%'";
if( $_POST['search_type'] )$dWhere .= " and  type=".$_POST['search_type'];
if( $_POST['search_genre'] )$dWhere .= " and genre=".$_POST['search_genre'];
if( $_POST['mail'] ){
	$mail = Get_Table_Row("mail_scenario_data"," WHERE mail = '".addslashes($_POST['mail'])."'");
	if(!$mail['scenario_id']) $mail['scenario_id']=0;
	$dWhere .= " and id=".$mail['scenario_id'];
}

// 表示順設定
$order = $_GET['sort'] ? $_GET['sort'] : "date DESC";
$order = " order by ".$order ;


$dSql = "SELECT count(*) FROM " . $table ;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . $dWhere  ;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT sum(total) FROM " . $table . $dWhere ;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dHit_Total = $dRtn3->fetch_row();

$dSql = "SELECT sum(total) FROM " . $table ;
$dRtn4 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Total = $dRtn4->fetch_row();

$dSql = "SELECT * FROM " . $table . $dWhere .  $order . " LIMIT ".$dStart.",".$dLine_Max;
$list = Get_Result_Sql_Array( $dSql );

?>