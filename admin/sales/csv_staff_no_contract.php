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
	$staff_list[$result['id']] = $result['name'];
	$staff_code[$result['id']] = $result['code'];// 従業員番号
}
//var_dump($staff_code);exit;

// 未契約数取得 ----------------------------------------------------------------------
// 区分：一般
// 予約区分：カウンセリング
// 来店状況：未契約
// 同お客さまで契約がなく、未来の予約も入っていないこと
// スタッフの取り方 2.公開 OR (1.非公開 AND 退職日あり OR 退職日 >= 検索エンド日)
$dSql = " SELECT r.cstaff_id,st.shop_id,count(r.id) AS no_contract_count, (

SELECT h3.name
FROM staff s3, shop h3
WHERE s3.id = r.cstaff_id
AND s3.shop_id = h3.id
) '所属名'  FROM reservation r,staff st,customer c
		  WHERE r.del_flg=0 AND r.status=2 AND r.type=1 AND r.hope_date>='".$_POST['contract_date']."' AND r.hope_date<='".$_POST['contract_date2']."'";
$dSql .= "  AND r.customer_id=c.id AND r.cstaff_id=st.id AND c.del_flg=0 AND c.ctype=1 ";
$dSql .= "	AND NOT EXISTS (SELECT t.customer_id FROM contract t WHERE t.del_flg =0 AND c.id = t.customer_id)
			
			AND (st.status=2 or (st.status=1 and end_day='0000-00-00' or end_day>='".$_POST['contract_date']."'))";
		//	AND NOT EXISTS (SELECT r2.customer_id FROM reservation r2 WHERE r2.del_flg =0 AND r2.customer_id = r.customer_id AND  r2.hope_date > now())
if( $_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['staff_id'] ) $dSql .= " AND r.cstaff_id = '".addslashes($_POST['staff_id'])."'";
$dSql .=" GROUP BY r.cstaff_id ORDER BY st.shop_id,st.id DESC ";
//echo $dSql;exit;
$dRtn = $GLOBALS['mysqldb']->query( $dSql );

//csv export----------------------------------------------------------------------
$filename = "sales_staff_not_contract.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn->num_rows >= 1 ) {
	echo mb_convert_encoding("社員番号,カウンセラー,所属名,契約店舗,未契約者数\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn->fetch_assoc() ) {
		echo mb_convert_encoding($staff_code[$data['cstaff_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($staff_list[$data['cstaff_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['所属名'],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['no_contract_count'] . ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}


?>
