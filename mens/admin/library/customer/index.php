<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

$table = "customer";
if(!isset($_POST['ctype'])) $_POST['ctype'] =1;

$_POST['reg_date']=$_POST['reg_date'] ? $_POST['reg_date'] : ($_POST['reg_date2'] ? $_POST['reg_date2'] : date("2016-03-10"));
$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : date("Y-m-d");

$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : ($_POST['reg_date'] ? $_POST['reg_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['reg_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['reg_date2']." +1day"));


// データの仮削除------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	// 顧客データ仮削除
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// 予約データも仮削除
	$sql = "UPDATE reservation SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// 契約データも仮削除
	$sql = "UPDATE contract SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// 売上データも仮削除
	$sql = "UPDATE contract_P SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// 売上データも仮削除
	$sql = "UPDATE sales SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// 消化履歴データも仮削除 20160825 add by shimada
	$sql = "UPDATE r_times_history SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// 問診票データも仮削除 20160825 add by shimada
	$sql = "UPDATE sheet SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// カウンセリングカルテデータも仮削除 20160825 add by shimada
	$sql = "UPDATE karte_c SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	// トリートメントカルテデータも仮削除 20160825 add by shimada
	$sql = "UPDATE karte SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE customer_id = '".addslashes($_REQUEST['id'])."'";

	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	
	// 広告カウント数調整
	$data = Get_Table_Row($table," WHERE route=1 AND id = '".addslashes($_REQUEST['id'])."'");
	if($data['id']){
		$accesslog3 = Get_Table_Row("accesslog"," WHERE job_flg=0 AND count>0 AND page_id=3 AND adcode='{$data['adcode']}' AND access_date='".substr($data['reg_date'],0,10)."' AND mo_agent={$data['mo_agent']} ");
		if(isset($accesslog3 )){
			$sql3 = "UPDATE accesslog SET count = {$accesslog3['count']}-1";
			$sql3 .= " WHERE job_flg=0 AND count>0 AND page_id=3 AND adcode='{$data['adcode']}' AND access_date='".substr($data['reg_date'],0,10)."' AND mo_agent={$data['mo_agent']}";
			
			$dRes3 = $GLOBALS['mysqldb']->query($sql3) or die('query error'.$GLOBALS['mysqldb']->error);
		}
		$accesslog14 = Get_Table_Row("accesslog"," WHERE job_flg=0 AND count>0 AND page_id=14 AND adcode='{$data['adcode']}' AND access_date='".substr($data['reg_date'],0,10)."' AND mo_agent={$data['mo_agent']} ");
		if(isset($accesslog14 )){
			$sql14 = "UPDATE accesslog SET count = {$accesslog14['count']}-1";
			$sql14 .= " WHERE job_flg=0 AND count>0 AND page_id=14 AND adcode='{$data['adcode']}' AND access_date='".substr($data['reg_date'],0,10)."' AND mo_agent={$data['mo_agent']}";
			$dRes14 = $GLOBALS['mysqldb']->query($sql14) or die('query error'.$GLOBALS['mysqldb']->error);
		}
	}

	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere = "";

$dWhere .= " AND  reg_date>='".$_POST['reg_date']." 00:00:00'";
$dWhere .= " AND  reg_date<='".$_POST['reg_date2']." 23:59:59'";
if($_POST['search_shop_id'] !=0) $dWhere .= " AND  shop_id='".($_POST['search_shop_id'])."'";
if( $_POST['adcode'] !=0 ) $dWhere .= " AND adcode = '".addslashes($_POST['adcode'])."'";
if( $_POST['rebook_flg'] !="" ) $dWhere .= " AND rebook_flg = '".addslashes($_POST['rebook_flg'])."'";
if( $_POST['route'] !="" ) $dWhere .= " AND route = '".addslashes($_POST['route'])."'";

$rWhere = $dWhere; // 最申込件数集計用

if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " or no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"aSKV", "UTF-8") )."%'";
	$dWhere .= " or replace(tel, '-', '') LIKE '%".addslashes( str_replace("-","",mb_convert_kana($_POST['keyword'],"a", "UTF-8")) )."%'";
	$dWhere .= " or address LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
} elseif ( $_GET['tel'] != "" ) {
	$dWhere .= " AND ";
	//検索速度向上のため、以下サブクエリを挟んでいます(全体検索にreplaceを入れると検索速度が低下する問題)
	$tel_param = "(SELECT DISTINCT(tel) FROM customer WHERE (replace(tel, '-', '') = '" .$_GET['tel']. "' ))";
	//電話番号検索
	$dWhere .= " tel =" .$tel_param ;
}
if( $_POST['status'] !=0 ) $dWhere .= " AND status = '".addslashes($_POST['status'])."'";
if( $_POST['ctype'] !=0 ) $dWhere .= " AND ctype = '".addslashes($_POST['ctype'])."'";

// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM ".$table. " WHERE del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg = 0".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];


$dSql = "SELECT * FROM " . $table . " WHERE del_flg = 0".$dWhere." ORDER BY reg_date DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
//var_dump($dSql );
$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg = 0 and adcode<>'' ".$dWhere;
$dRtn4 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt4 = $dRtn4->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg = 0 and route=2 ".$dWhere;
$dRtn5 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt5 = $dRtn5->fetch_row();

// 再申込件数集計。予約変更：act_flg=１、キャンセル：act_flg=2、actionなし：act_flg=0。予約変更が再申込対象外
$dSql = "SELECT count(id) FROM reservation WHERE del_flg = 0 and rebook_flg<>0 and act_flg=0 ".$rWhere;
$dRtn6 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt6 = $dRtn6->fetch_row();




// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();
// $nensdb = changedb();

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}


// ADリスト
$adcode_sql = $GLOBALS['mysqldb']->query( "select * from adcode order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
$adcode_list[0] = "全媒体";
while ( $result = $adcode_sql->fetch_assoc() ) {
	$adcode_list[$result['id']] = $result['name'];
}

$hidden='<input name="keyword" type="hidden" value="'.$_POST['keyword'].'">
		<input name="start" type="hidden" value="'.$_POST['start'].'">
		<input name="reg_date" type="hidden" value="'.$_POST['reg_date'].'">
		<input name="reg_date2" type="hidden" value="'.$_POST['reg_date2'].'">
		<input name="status" type="hidden" value="'.$_POST['status'].'">

		<input name="shop_id" type="hidden" value="'.$_POST['shop_id'].'">';

?>
