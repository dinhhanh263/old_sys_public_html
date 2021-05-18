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
include_once( "../../lib/auth.php" );

$table = "contract";

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");
//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}

//------------------------------------------------------------------------------------

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : ($_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-01"));
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-d");

$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date2']." +1day"));

//staffリスト
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['contract_date']."') ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];// 従業員名
	$staff_code[$result['id']] = $result['code'];// 従業員番号
	$staff_shop[$result['id']] = $shop_list[$result['shop_id']];// 所属店舗
}

// 検索条件の設定-------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

//支払完了
// ソート設定：なし
if($_POST['if_sort']==0){
	// 自動ソート：支払完了日順
	// 残金状況：支払完了
if($_POST['if_balance']==1){
	$order = "pay_complete_date";
	}
	// 自動ソート：契約日順
	// 契約区分：クーリングオフ、中途解約、自動解約以外
	elseif($_POST['status']<>2 && $_POST['status']<>3 && $_POST['status']<>6){
		$order = "contract_date";
	}
	// 自動ソート：解約日順
	// 契約区分：クーリングオフ、中途解約、自動解約
	elseif($_POST['status']==2 || $_POST['status']==3 || $_POST['status']==6){
		$order = "cancel_date";
	}
}
// ソート設定：あり
// ソート設定：契約日順
elseif($_POST['if_sort']==1){
	$order = "contract_date";
}
// ソート設定：解約日順
elseif($_POST['if_sort']==2){
	$order = "cancel_date";
}
// ソート設定：支払完了日順
elseif($_POST['if_sort']==3){
	$order = "pay_complete_date";
}
$dWhere .= " AND t.".$order.">='".$_POST['contract_date']."'";
$dWhere .= " AND t.".$order."<='".$_POST['contract_date2']."'";

//残金あり
if($_POST['if_balance']==1){
	$order = $order;
}
// 【残金状況】残金あり
elseif($_POST['if_balance']==2){
	$dWhere .= " AND t.balance>0";
}

//if( $_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";

if( $_POST['shop_id'] ) $dWhere .= " AND  t.shop_id='".($_POST['shop_id'] )."'";
if( -1<$_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'"; // 契約状況
if( $_POST['staff_id'] ) $dWhere .= " AND t.staff_id = '".addslashes($_POST['staff_id'])."'";


// データの取得----------------------------------------------------------------------
// $dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " t,customer c WHERE t.customer_id=c.id AND t.status=0 AND c.del_flg=0 AND t.del_flg = 0".$dWhere." ORDER BY t.".$order;
$dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana FROM " . $table . " t,customer c WHERE t.customer_id=c.id AND c.del_flg=0 AND t.del_flg = 0 AND t.status<>5 AND NOT(t.status=0 AND 1000<t.course_id)".$dWhere." ORDER BY t.".$order; // AND t.status=0除外 AND t.status<>5追加(契約中の条件削除、ローン取消以外追加) AND NOT(t.status=0 AND 1000<t.course_id)追加(契約中で返金保証期間終了後のコースは除外)

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
//var_dump($dSql);exit;
//csv export----------------------------------------------------------------------
$filename = "sales_staff.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("会員番号,名前,名前カナ,区分,店舗,契約日,解約日,旧コース,新コース,契約金額,請求金額,実入金額,売掛金,支払完了日,社員番号,カウンセラー,所属店舗\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		if( ($_POST['if_balance']==1) && $data['payment_loan'] && $data['loan_status']<>1) continue;

		if($data['status']==5) $price=$data['price']-$data['balance']-$data['payment_loan'];																				//ローン取消の場合、ローンを０に
		else $price = $data['price']-$data['balance'];

		echo $data['no']  . ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		//echo mb_convert_encoding($gContractStatus[$data['status']],"SJIS-win", "UTF-8")  . ",";
		if(!$course_type[$data['course_id']]){
			echo 	mb_convert_encoding(($data['status']==4 && $data['conversion_flg'] ? "プラン組替" : $gContractStatus7[$data['status']]),"SJIS-win", "UTF-8")  . ",";
		} else {
			echo 	mb_convert_encoding($gContractStatus6[$data['status']],"SJIS-win", "UTF-8")  . ",";
		}
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['contract_date'] . ","; 
		echo ($data['cancel_date']<>"0000-00-00" ? $data['cancel_date'] : "") . ","; 

		// プラン変更済みの場合、旧コースを表示する
		if($data['old_course_id']<>0){
			echo mb_convert_encoding($course_list[$data['old_course_id']],"SJIS-win", "UTF-8")  . ",";
		} else {
			echo ",";
		}
		// 新コース(今の契約コース)
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8")  . ",";
		echo ($data['fixed_price']-$data['discount']) . ",";
		echo $data['price'] . ","; 
		if($data['status']==2 || $data['status']==3){
		 echo 0 . ",";  					//実入金額(クーリングオフ、中途解約)
		}else{	
		 echo $price . ",";  				//実入金額,複数支払のため、
		}
		echo $data['balance'] . ","; 
		echo ($data['pay_complete_date']<>"0000-00-00" ? $data['pay_complete_date'] : "") . ","; 
		echo mb_convert_encoding($staff_code[$data['staff_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($staff_list[$data['staff_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($staff_shop[$data['staff_id']],"SJIS-win", "UTF-8")  . ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
