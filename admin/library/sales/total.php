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

$table = "sales";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date']." +1day"));
if(!array_key_exists("shop_id", $_POST)) $_POST["shop_id"] = "0";
if(!array_key_exists("block_code", $_POST)) $_POST["block_code"] = "-1";
if(!array_key_exists("area_code", $_POST)) $_POST["area_code"] = "-1";


if($_REQUEST['mode']=="display" || $authority_shop['id'] || $_POST['customer_id']){
	// 店舗アカウントの場合の該当店舗のみ検索可
	if ($authority_shop['id']) $_POST['shop_id'] = $authority_shop['id'];
	
	// データの取得------------------------------------------------------------------------
	if ($_POST['type'] >= "0" && $_POST['type'] <= "9") {
		$dSql = "SELECT
	--	sales.shop_id AS '店舗番号' ,
	--	(SELECT name FROM shop WHERE id = sales.shop_id) AS '店舗名',
		SUM(CASE WHEN sales.type = 1 OR sales.type = 13 THEN sales.price ELSE 0 END) + SUM(CASE WHEN sales.type = 32 THEN sales.price ELSE 0 END) + SUM(CASE WHEN sales.type = 6 OR sales.type = 10 THEN sales.price ELSE 0 END) + SUM(CASE WHEN sales.type = 8 THEN sales.option_price + sales.option_transfer + sales.option_card ELSE 0 END) + SUM(CASE WHEN sales.type = 2 THEN sales.option_price + sales.option_transfer + sales.option_card ELSE 0 END) + SUM(CASE WHEN sales.type = 51 THEN sales.option_price + sales.option_transfer + sales.option_card ELSE 0 END) + SUM(CASE WHEN (SELECT type FROM course WHERE id = sales.course_id) = 0 AND sales.type = 5 OR sales.type = 12 THEN round((contract.fixed_price - contract.discount) / contract.times , 0) * contract.r_times + sales.charge ELSE 0 END) - SUM(CASE WHEN (SELECT type FROM course WHERE id = sales.course_id) = 0 AND sales.type = 5 OR sales.type = 12 THEN contract.price ELSE 0 END) - SUM(CASE WHEN sales.type = 4 THEN sales.price ELSE 0 END) AS '総売上' , -- 0
		SUM(CASE WHEN sales.type = 1 OR sales.type = 13 THEN sales.price ELSE 0 END) AS '新規プラン契約金額' , -- 1
		SUM(CASE WHEN sales.type = 32 THEN sales.price ELSE 0 END) AS '追加契約金額' , -- 2
		SUM(CASE WHEN sales.type = 6 OR sales.type = 10 THEN sales.price ELSE 0 END) AS 'ミドル金額' , -- 3
		SUM(CASE WHEN sales.type = 8 THEN sales.option_price + sales.option_transfer + sales.option_card ELSE 0 END) AS '月額支払金額' , -- 4
		SUM(CASE WHEN sales.type = 2 THEN sales.option_price + sales.option_transfer + sales.option_card ELSE 0 END) AS '消化付帯売上' , -- 5
		SUM(CASE WHEN sales.type = 51 THEN sales.option_price + sales.option_transfer + sales.option_card ELSE 0 END) AS '物販金額' , -- 6
		SUM(CASE WHEN (SELECT type FROM course WHERE id = sales.course_id) = 0 AND sales.type = 5 OR sales.type = 12 THEN round((contract.fixed_price - contract.discount) / contract.times , 0) * contract.r_times + sales.charge ELSE 0 END) AS '解約精算金額' , -- 7
		SUM(CASE WHEN (SELECT type FROM course WHERE id = sales.course_id) = 0 AND sales.type = 5 OR sales.type = 12 THEN contract.price ELSE 0 END) AS '解約金額' , -- 8
		SUM(CASE WHEN sales.type = 4 THEN sales.price ELSE 0 END) AS 'クーリングオフ金額' -- 9
		FROM sales LEFT OUTER JOIN contract ON sales.contract_id = contract.id AND contract.del_flg = 0
		INNER JOIN shop on sales.shop_id = shop.id
		WHERE 1 = 1 AND sales.del_flg = 0";
		$dSql .= " AND sales.pay_date BETWEEN '".$_POST['pay_date']."' AND '".$_POST['pay_date2']."'";
		if(array_key_exists("shop_id", $_POST) && $_POST['shop_id'] !== "0") $dSql .= " AND sales.shop_id='".$_POST['shop_id'] ."'";
		if(array_key_exists("block_code", $_POST) && $_POST['block_code'] !== "-1") $dSql .= " AND shop.block_code='".$_POST['block_code'] ."'";
		if(array_key_exists("area_code", $_POST) && $_POST['area_code'] !== "-1") $dSql .= " AND shop.area_code='".$_POST['area_code'] ."'";

		$dRtn = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

		$result_sum = 0;
		$resultTotal = $dRtn->fetch_all();
		foreach ($resultTotal as $value) {
			$result_sum += $value[$_POST['type']];
		}
	} elseif ($_POST['type'] == "10") {
		$dSql2 = "SELECT
		count(type = 4 OR NULL) AS 'クーオフ件数',
		ROUND(count(type = 4 OR NULL) / count(type= 1 OR NULL),2) AS 'クーオフ率'
	    FROM sales 
	    INNER JOIN shop on sales.shop_id = shop.id 
	    WHERE sales.del_flg = 0 
		AND (SELECT ctype FROM customer WHERE id = sales.customer_id) = 1 
		AND (SELECT name FROM customer WHERE id = sales.customer_id) NOT LIKE '%テスト%'";
	    $dSql2 .= " AND sales.pay_date BETWEEN '".$_POST['pay_date']."' AND '".$_POST['pay_date2']."'";
	    if(array_key_exists("shop_id", $_POST) && $_POST['shop_id'] !== "0") $dSql2 .= " AND sales.shop_id='".$_POST['shop_id'] ."'";
	    if(array_key_exists("block_code", $_POST) && $_POST['block_code'] !== "-1") $dSql2 .= " AND shop.block_code='".$_POST['block_code'] ."'";
	    if(array_key_exists("area_code", $_POST) && $_POST['area_code'] !== "-1") $dSql2 .= " AND shop.area_code='".$_POST['area_code'] ."'";
		
		$dRtn2 = $GLOBALS['mysqldb']->query( $dSql2 ) or die('query error'.$GLOBALS['mysqldb']->error);

		$result_sum2 = 0;
		$resultTotal2 = $dRtn2->fetch_all();
		foreach ($resultTotal2 as $value) {
			$result_sum_9_1 += $value[0]; // クーリングオフ件数
			$result_sum_9_2 += $value[1]; // クーリングオフ率
		}
		$result_sum_9_2 = $result_sum_9_2 * 100;


    } elseif ($_POST['type'] == "11") {
		$dSql = "SELECT
		    count(*) AS 'CO数'
	    FROM reservation r
	    INNER JOIN shop on r.shop_id = shop.id
	    WHERE r.del_flg = 0 
	    AND r.status IN(2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30)
	    AND r.type = 1 
	    AND (SELECT ctype FROM customer WHERE id = r.customer_id) = 1 
	    AND (SELECT name FROM customer WHERE id = r.customer_id) NOT LIKE '%テスト%'";
	    $dSql .= " AND r.hope_date BETWEEN '".$_POST['pay_date']."' AND '".$_POST['pay_date2']."'";
		if(array_key_exists("shop_id", $_POST) && $_POST['shop_id'] !== "0") $dSql .= " AND r.shop_id='".$_POST['shop_id'] ."'";
		if(array_key_exists("block_code", $_POST) && $_POST['block_code'] !== "-1") $dSql .= " AND shop.block_code='".$_POST['block_code'] ."'";
		if(array_key_exists("area_code", $_POST) && $_POST['area_code'] !== "-1") $dSql .= " AND shop.area_code='".$_POST['area_code'] ."'";
		
		$dRtn = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

		$result_sum = 0;
		$resultTotal = $dRtn->fetch_all();
		foreach ($resultTotal as $value) {
			$result_sum += $value[0];
		}

		$dSql2 = "SELECT
		count(sales.type= 1 OR NULL) AS '成約件数',
		ROUND(SUM(CASE WHEN type = 1 THEN price END) / count(type= 1 OR NULL),0) AS '成約単価'
	    FROM sales 
	    INNER JOIN shop on sales.shop_id = shop.id
	    WHERE sales.del_flg = 0 
		AND (SELECT ctype FROM customer WHERE id = sales.customer_id) = 1 
		AND (SELECT name FROM customer WHERE id = sales.customer_id) NOT LIKE '%テスト%' ";
	    $dSql2 .= " AND sales.pay_date BETWEEN '".$_POST['pay_date']."' AND '".$_POST['pay_date2']."'";
	    if(array_key_exists("shop_id", $_POST) && $_POST['shop_id'] !== "0") $dSql2 .= " AND sales.shop_id='".$_POST['shop_id'] ."'";
	    if(array_key_exists("block_code", $_POST) && $_POST['block_code'] !== "-1") $dSql2 .= " AND shop.block_code='".$_POST['block_code'] ."'";
	    if(array_key_exists("area_code", $_POST) && $_POST['area_code'] !== "-1") $dSql2 .= " AND shop.area_code='".$_POST['area_code'] ."'";
		
		$dRtn2 = $GLOBALS['mysqldb']->query( $dSql2 ) or die('query error'.$GLOBALS['mysqldb']->error);

		$result_sum2 = 0;
		$resultTotal2 = $dRtn2->fetch_all();
		foreach ($resultTotal2 as $value) {
			$result_sum2 += $value[0]; // 成約件数
			$result_sum4 += $value[1]; // 成約単価
		}

		$result_sum3 = round(($result_sum2 / $result_sum) * 100, 0); // 成約率

	} elseif ($_POST['type'] == "12") {
		$dSql = "SELECT
        count(*) AS '純消化数' -- 12
        FROM sales 
        INNER JOIN shop on sales.shop_id = shop.id
        WHERE sales.del_flg = 0 
        AND sales.contract_id > 0 
        AND (SELECT ctype FROM customer WHERE id = customer_id) < 100 
        AND (SELECT name FROM customer WHERE id = customer_id) NOT LIKE '%テスト%' 
        AND sales.type IN (2,3,14) 
        AND sales.r_times > 0 ";
		$dSql .= " AND sales.pay_date BETWEEN '".$_POST['pay_date']."' AND '".$_POST['pay_date2']."'";
		if(array_key_exists("shop_id", $_POST) && $_POST['shop_id'] !== "0") $dSql .= " AND sales.shop_id='".$_POST['shop_id'] ."'";
	    if(array_key_exists("block_code", $_POST) && $_POST['block_code'] !== "-1") $dSql .= " AND shop.block_code='".$_POST['block_code'] ."'";
	    if(array_key_exists("area_code", $_POST) && $_POST['area_code'] !== "-1") $dSql .= " AND shop.area_code='".$_POST['area_code'] ."'";
	
		$dRtn = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

		$result_sum = 0;
		$resultTotal = $dRtn->fetch_all();
		foreach ($resultTotal as $value) {
			$result_sum += $value[0];
		}
	}
}

// 店舗リスト----------------------------------------------------------------------------------
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 AND assign = 3 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "未選択";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
}

//courseタイプ---------------------------------------------------------------------------------
$type = [
0=>"総売上",
1=>"新規プラン契約金額",
2=>"追加契約金額",
3=>"ミドル金額",
4=>"月額支払金額",
5=>"消化付帯売上",
6=>"物販金額",
7=>"解約精算金額",
8=>"解約金額",
9=>"クーリングオフ金額",
10=>"クーリングオフ率",
11=>"成約率",
12=>"純消化数"
];

// ブロックコード
$block_sql = $GLOBALS['mysqldb']->query( "select block_code from shop WHERE del_flg = 0 AND status=2 AND assign = 3 AND block_code is not null group by block_code order by block_code" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $block_result = $block_sql->fetch_assoc() ) {
	$block_list[$block_result['block_code']] = $block_result['block_code'];
}

// エリアコード
$area_code_sql = $GLOBALS['mysqldb']->query( "select area_code from shop WHERE del_flg = 0 AND status=2 AND assign = 3 AND area_code is not null group by area_code order by area_code" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $area_code_result = $area_code_sql->fetch_assoc() ) {
	$area_code_list[$area_code_result['area_code']] = $area_code_result['area_code'];
}

?>