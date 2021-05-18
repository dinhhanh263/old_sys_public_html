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

// $table = "sales_view";
$table = "sales";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date']." +1day"));

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

if($_POST['shop_id']) $dWhere .= " AND s.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";

// 月額コースID取得
$month_id = implodeArray("course","id"," where del_flg=0 and type=1");

if( $_POST['type']==20 ) $dWhere .= " AND s.type =5 AND s.course_id in(".$month_id." )";
elseif( $_POST['type']==5 ) $dWhere .= "  AND s.type =5 AND s.course_id not in(".$month_id." )";
elseif( $_POST['type'] ) $dWhere .= " AND s.type = '".addslashes($_POST['type'])."'";

if( $_POST['option_name'] ) $dWhere .= " AND s.option_name = '".addslashes($_POST['option_name'])."'";
if( $_POST['course_id'] ) $dWhere .= " AND s.course_id = '".addslashes($_POST['course_id'])."'";
if( $_POST['is_loan_only'] ) $dWhere .= " AND s.payment_loan <>0";
if( $_POST['customer_id'] && !$_POST['keyword'] ){
	$dWhere .= " AND s.customer_id='".$_POST['customer_id'] ."'";
	if( $_POST['contract_id'] != "" ) {
		$dWhere .= " AND s.contract_id='".$_POST['contract_id'] ."'";
	}
}else{
	$dWhere .= " AND  s.pay_date>='".$_POST['pay_date']."'";
	$dWhere .= " AND  s.pay_date<='".$_POST['pay_date2']."'";
}
if($_REQUEST['mode']=="display" || $authority_shop['id'] || $_POST['customer_id']){

	// データの取得---------------------------------------------------------------------------------------------------

	// $dSql  = "SELECT * FROM " . $table . " WHERE id > 0".$dWhere." ORDER BY pay_date,reg_date ";
	$dSql = "SELECT s.*, c.no AS no, c.name AS name, c.name_kana AS name_kana, c.tel AS tel, c.mail AS mail, t.loan_status AS loan_status, t.conversion_flg AS conversion_flg ";
	$dSql .= "FROM (customer c JOIN (" . $table . " s LEFT JOIN contract t ON (t.id = s.contract_id AND t.del_flg = 0))) WHERE (s.customer_id = c.id AND c.del_flg = 0 AND s.del_flg = 0 AND (s.r_times = 0 OR s.payment_cash != 0 OR s.payment_card != 0 OR s.payment_transfer != 0 OR s.payment_loan != 0 OR s.payment_coupon != 0 OR s.option_price != 0 OR s.option_transfer != 0 OR s.option_card != 0))" . $dWhere . " ORDER BY s.pay_date, s.reg_date";
	$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

  // 顧客別の場合非表示
  if(!$_POST['customer_id']){

	//未契約数取得 --------------------------------------------------------------------------------------------------
	$dSql = "SELECT COUNT( DISTINCT customer_id ) FROM nocontract_view
			WHERE id >0	AND hope_date>='".$_POST['pay_date']."' AND hope_date<='".$_POST['pay_date2']."'";

	if($_POST['shop_id'] )$dSql .= " AND shop_id='".$_POST['shop_id'] ."'";

	//$dRtn4 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
	//$dGet_Cnt4 = $dRtn4->fetch_row();

	// 来店なし数取得 -------------------------------------------------------------------------------------------------
	// $dSql = "SELECT COUNT(DISTINCT r.customer_id) FROM customer AS c, reservation AS r,
	// 		(
	// 			SELECT r.customer_id, max(r.id) as id FROM	reservation AS r,
	// 		    (
	// 		    	SELECT distinct r.customer_id FROM reservation AS r,
	// 		        (
	// 		        	SELECT customer_id, max(id) as id, max(status) as mstatus FROM reservation WHERE del_flg = 0 GROUP BY customer_id
	// 		        ) AS r2
	// 		        WHERE r.id = r2.id AND mstatus < 2 AND r.hope_date <= now()
	// 		    ) AS r2
	// 		    WHERE r.customer_id = r2.customer_id
	// 		    AND r.del_flg = 0
	// 		    AND r.status < 2
	// 		    GROUP BY r.customer_id
	// 		) AS r2
	// 		WHERE r.id = r2.id
	// 		AND c.id = r.customer_id
	// 		AND c.id = r2.customer_id
	// 		AND c.del_flg = 0
	// 		AND r.del_flg = 0
	// 		AND c.ctype = 1";
	// $dSql .= " AND r.hope_date>='".$_POST['pay_date']."' AND r.hope_date<='".$_POST['pay_date2']."'";
	// if($_POST['shop_id'] )$dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'";

	//$dRtn5 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
	//$dGet_Cnt5 = $dRtn5->fetch_row();
  }
}

// 店舗リスト----------------------------------------------------------------------------

$shop_list = getDatalist("shop","全店舗");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

// courseリスト------------------------------------------------------------------------

$course_list[0] = "全コース";
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
}

// 売上のviewの作成
$sales_view_sql='
 CREATE VIEW sales_view AS
  SELECT
    s.id AS id,
    s.type AS type,
    s.shop_id AS shop_id,
    s.pay_date AS pay_date,
    s.customer_id AS customer_id,
    c.no AS no,
    c.name AS name,
    c.name_kana AS name_kana,
    c.tel AS tel,
    c.mail AS mail,
    s.course_id AS course_id,
    s.r_times AS r_times,
    s.fixed_price AS fixed_price,
    s.discount AS discount,
    s.price AS price,
    s.balance AS balance,
    s.payment_cash AS payment_cash,
    s.payment_card AS payment_card,
    s.payment_transfer AS payment_transfer,
    s.payment_loan AS payment_loan,
    s.payment_coupon AS payment_coupon,
    s.option_price AS option_price,
    s.option_transfer AS option_transfer,
    s.option_card AS option_card,
    s.reg_date AS reg_date,
    t.loan_status AS loan_status,
    t.conversion_flg AS conversion_flg
    FROM customer AS c,sales AS s
    LEFT JOIN `contract` t ON t.id = s.contract_id AND t.del_flg = 0 
    WHERE s.customer_id=c.id AND c.del_flg=0 AND s.del_flg = 0
    AND !( s.r_times AND !s.payment_cash AND !s.payment_card AND !s.payment_transfer AND !s.payment_loan AND !s.payment_coupon AND !s.option_price AND !s.option_price AND !s.option_transfer AND !s.option_card )
    ORDER BY s.pay_date,s.reg_date;
';

// 未契約数のviewの作成
$nocontract_view_sql='
 CREATE VIEW nocontract_view AS
  SELECT
    r.id AS id,
    r.customer_id AS customer_id,
    c.ctype AS ctype,
    c.status AS status,
    r.shop_id AS shop_id,
    r.hope_date AS hope_date,
    
    FROM customer AS c, reservation AS r
    WHERE c.del_flg =0
	  AND r.del_flg =0
	  AND c.id = r.customer_id
	  AND c.ctype =1
	  AND r.status >=2
	  AND NOT EXISTS (SELECT t.customer_id FROM contract t WHERE t.del_flg =0 AND c.id = t.customer_id);
';

// 来店なし数のviewの作成
$nocome_view_sql='
 CREATE VIEW nocome_view AS
  SELECT
    r.id AS id,
    r.customer_id AS customer_id,
    c.ctype AS ctype,
    c.status AS status,
    c.shop_id AS shop_id,
    c.hope_date AS hope_date,
    
    FROM customer AS c, reservation AS r,
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
	 AND c.ctype = 1;
';
?>