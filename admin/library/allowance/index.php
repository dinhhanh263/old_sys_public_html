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

$table = "staff";

$ym = $_POST['ym']=$_POST['ym'] ? $_POST['ym'] : date("Y/m");


//目標達成情報------------------------------------------------------------------------
$ymd = str_replace("/", "-", $_POST['ym'])."-01";
$ym2 = date("Y/m", strtotime( $ymd."-2 month"));
$ymd2 = str_replace("/", "-", $ym2)."-01";
$goal = $data = Get_Table_Row("goal"," WHERE del_flg=0 and ym2 = '".addslashes($ym2)."'");

// 新規or編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit") {
	// 手当発生月に強制指定
	$_POST['ym'] = $ym2;
	$field = array("shop_id","staff_id","ym","work_location","base_salary","type","coun_allowance","trea_allowance","train_allowance","trav_allowance","intro_allowance","president_award","achi_allowance","sales" );
    $_POST['reg_date'] = $_POST['edit_date'] = date('Y-m-d H:i:s');

	if($_POST['id']){
		array_push($field,  "edit_date");
		$data_ID = Update_Data("allowance",$field,$_POST['id']);
	}else{
		array_push($field,  "reg_date");
		$data_ID = Input_New_Data("allowance",$field);
	} 

	//　手当発生月からもどり
	$_POST['ym'] = $ym;
}

// 表示ページ設定------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 9999;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere = "";
if( $_POST['shop_id'] ) $dWhere .= " AND  s.shop_id='".($_POST['shop_id'] )."'";
if( $_POST['staff_id'] ) $dWhere .= " AND  s.id='".($_POST['staff_id'] )."'";

// データの取得------------------------------------------------------------------------
if($_REQUEST['mode']=="display"){
	$dSql = "SELECT s.*,p.base_salary,p.allowance FROM " . $table . " as s,posi_salary as p WHERE s.del_flg = 0 and s.type=p.position and (s.end_day='0000-00-00' OR s.end_day>='".$ymd2."') and s.type not in(19,21) ".$dWhere." order by s.type,s.id";
	$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
}
// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist("shop");

// staff list
// $staff_list = getDatalist("staff","-");

// 役職手当 list
$posi_salary_sql = $GLOBALS['mysqldb']->query( "select * from posi_salary WHERE del_flg = 0 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
if($posi_salary_sql){
	while ( $result = $posi_salary_sql->fetch_assoc() ) {
		$posi_salary_list[$result['position']] = $result['allowance'];
	}
}

// 旧月額コースID取得
$old_month_id = implodeArray("course","id"," WHERE del_flg=0 AND type=1 AND new_flg=0");

// カウンセリング手当、施術手当対象
$obj_c = $obj_t = array("8","9","10","11","12","13","15","17","18","22","23","24","30");

?>