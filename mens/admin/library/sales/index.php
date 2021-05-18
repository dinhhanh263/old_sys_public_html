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

$table = "sales";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date']." +1day"));


// データの仮削除------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 20;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

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

if($_POST['shop_id']) $dWhere .= " AND  s.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['status'] ) $dWhere .= " AND s.status = '".addslashes($_POST['status'])."'";

// 月額コースID取得
// $month_id = implodeArray("course","id"," where del_flg=0 and type=1");
// if( $_POST['type']==20 ) $dWhere .= " AND s.type =5 AND s.course_id in(".$month_id." )";
// elseif( $_POST['type']==5 ) $dWhere .= "  AND s.type =5 AND s.course_id not in(".$month_id."  )";
// elseif( $_POST['type'] ) $dWhere .= " AND s.type = '".addslashes($_POST['type'])."'";
// if( $_POST['type'] ) $dWhere .= " AND s.type = '".addslashes($_POST['type'])."'"; // 区分

// 区分検索条件 ※売掛回収はtype=2,27どちらも表示する
if( $_POST['type'] ==7 || $_POST['type'] ==27 ){ 
	$dWhere .= " AND s.type in(7,27)"; // 2.売掛回収 OR 27.トリートメント/売掛回収
} elseif($_POST['type']) {
	$dWhere .= " AND s.type = '".addslashes($_POST['type'])."'"; // それ以外の区分
}
// オプション名
if( $_POST['option_name'] ) $dWhere .= " AND s.option_name = '".addslashes($_POST['option_name'])."'";
// if( $_POST['course_id'] ) $dWhere .= " AND s.course_id = '".addslashes($_POST['course_id'])."'";
// if( $_POST['course_id'] ) $dWhere .= " AND s.course_id = '".addslashes($_POST['course_id'])."'";
if( $_POST['course_id'] ) $dWhere .= " AND FIND_IN_SET (".addslashes($_POST['course_id']).", s.multiple_course_id )"; // 複数コースIDは選んだコースを含むコースが表示される
if( $_POST['is_loan_only'] ) $dWhere .= " AND s.payment_loan <>0";
if( $_POST['customer_id']){
	$dWhere .= " AND s.customer_id='".$_POST['customer_id'] ."'";
}else{
	$dWhere .= " AND  s.pay_date>='".$_POST['pay_date']."'";
	$dWhere .= " AND  s.pay_date<='".$_POST['pay_date2']."'";
}

if($_REQUEST['mode']=="display" || $authority_shop['id'] || $_POST['customer_id']){
	
	// データの取得------------------------------------------------------------------------
	
	$dSql = "SELECT count(*) FROM ".$table. " s WHERE del_flg = 0";
	$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
	$dAll_Cnt = $dRtn1->fetch_row();

	// $dSql = "SELECT count(s.id) FROM " . $table . " s,customer c WHERE s.del_flg = 0".$dWhere;
	// $dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
	// $dGet_Cnt = $dRtn2->fetch_row()[0];

	$dSql  = "SELECT s.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel ";
	$dSql .= "FROM " . $table . " s,customer c WHERE s.customer_id=c.id AND c.del_flg=0 AND s.del_flg = 0".$dWhere;

	$dSql .= " ORDER BY s.pay_date,s.reg_date ";
	$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );


	// 未契約数取得 ----------------------------------------------------------------------
	// r.status=2 => r.status>=2 //その他での来店可能性も考慮　add by ka 20151211
	// 未来日に予約がある方は除外 add by ka 20151211

	$dSql = "SELECT COUNT( DISTINCT r.customer_id ) 
			FROM customer c, reservation r
			WHERE c.del_flg =0
			AND r.del_flg =0
			AND c.id = r.customer_id
			AND c.ctype =1
			AND r.status >=2
			AND r.hope_date>='".$_POST['pay_date']."' AND r.hope_date<='".$_POST['pay_date2']."'
			AND NOT EXISTS (SELECT t.customer_id FROM contract t WHERE t.del_flg =0 AND c.id = t.customer_id)
			AND NOT EXISTS (SELECT r2.customer_id FROM reservation r2 WHERE r2.del_flg =0 AND r2.customer_id = r.customer_id AND  r2.hope_date > now())";
	if($_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'"; 

	/* $dSql = 'SELECT COUNT(DISTINCT r.customer_id) FROM  reservation AS r, 
			(
			  SELECT r.customer_id, max(r.id) AS rid  FROM customer AS c, reservation AS r,
			  (
			  	SELECT r1.customer_id FROM reservation AS r1, 
			    (
			    	SELECT customer_id, max(id) AS mid FROM reservation WHERE del_flg =0 GROUP BY customer_id
			    ) AS r2
			    WHERE r1.customer_id = r2.customer_id AND r1.id = r2.mid AND r1.hope_date <= now()
			  ) AS c2
			  WHERE c.del_flg =0
			  AND r.del_flg =0
			  AND c.id = r.customer_id
			  AND r.customer_id= c2.customer_id
			  AND c.ctype =1
			  AND r.status >= 2
			  AND NOT EXISTS (SELECT t.customer_id FROM contract AS t WHERE t.del_flg =0 AND c.id = t.customer_id) 
			  GROUP BY r.customer_id
			) AS X
			WHERE r.id = X.rid';
	$dSql .= " AND r.hope_date>='".$_POST['pay_date']."' AND r.hope_date<='".$_POST['pay_date2']."'";		
	if($_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'"; */

	$dRtn4 = $GLOBALS['mysqldb']->query( $dSql );
	$dGet_Cnt4 = $dRtn4->fetch_row()[0];

	// 来店なし数取得 ---------------------------------------------------------------------------------------------------
	// contract_id=0の条件追加?20141205
	
	$dSql = "SELECT COUNT(DISTINCT r.customer_id) FROM customer AS c, reservation AS r,
			(
				SELECT r.customer_id, max(r.id) as id FROM	reservation AS r,
			    (
			    	SELECT distinct r.customer_id FROM reservation AS r,
			        (
			        	SELECT customer_id, max(id) as id, max(status) as mstatus FROM reservation WHERE del_flg = 0 GROUP BY customer_id
			        ) AS r2
			        WHERE r.id = r2.id AND mstatus < 2 AND r.hope_date <= now()
			    ) AS r2
			    WHERE r.customer_id = r2.customer_id
			    AND r.del_flg = 0
			    AND r.status < 2
			    GROUP BY r.customer_id
			) AS r2
			WHERE r.id = r2.id
			AND c.id = r.customer_id
			AND c.id = r2.customer_id
			AND c.del_flg = 0
			AND r.del_flg = 0
			AND c.ctype = 1";
	$dSql .= " AND r.hope_date>='".$_POST['pay_date']."' AND r.hope_date<='".$_POST['pay_date2']."'";		
	if($_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'";

	$dSql .= " ORDER BY c.adcode DESC , r.hope_date DESC , c.reg_date DESC";
	$dRtn5 = $GLOBALS['mysqldb']->query( $dSql );
	$dGet_Cnt5 = $dRtn5->fetch_row()[0];
}

// 店舗リスト----------------------------------------------------------------------------------

$shop_list = getDatalist_shop("全店舗");
// $mensdb = changedb();

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" );
//$shop_list[0] = "全店舗";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//}

//courseリスト---------------------------------------------------------------------------------

$course_list[0] = "全コース";
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

?>