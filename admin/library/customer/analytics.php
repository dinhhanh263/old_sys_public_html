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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

$_POST['mode'] = $_POST['mode'] ? $_POST['mode'] : "daily";

// テーブル設定
$table = "customer";

// 代理店リスト
$ad_name = $GLOBALS['mysqldb']->query( "SELECT ad_name FROM customer GROUP by ad_name ORDER BY ad_name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $ad_name->fetch_assoc() ) {
	$gAd_name[$list['ad_name']] = $list['ad_name'];
}

// 期間指定,前半1-15、後半16-末------------------------------------------------------------------------------------------

if($_POST['limit_year1'] != "" && $_POST['limit_month1'] != "" && $_POST['limit_day1'] != "" && $_POST['limit_year2'] != "" && $_POST['limit_month2'] != "" && $_POST['limit_day2'] != ""){
	$limit1_year = $_POST['limit_year1']; $limit1_month = $_POST['limit_month1']; $limit1_day = $_POST['limit_day1'];
	$limit2_year = $_POST['limit_year2']; $limit2_month = $_POST['limit_month2']; $limit2_day = $_POST['limit_day2'];
}else{
  	$limit1_year = date("Y"); $limit1_month = '01'; $limit1_day = '01';
  	$limit2_year  = date("Y",mktime(0, 0, 0, date("m") +1 , date("d"), date("Y")));
	$limit2_month = date("m",mktime(0, 0, 0, date("m") +1 , date("d"), date("Y")));
	$limit2_day = '0';
}

// 月前半指定
if($_POST['m_half'] == "1"){
	$limit1_day = '01';
	$limit2_day = '15';
	$limit2_month = $limit1_month;
	$select1 = " selected";
}

// 月後半指定
if($_POST['m_half'] == "2"){
	$limit1_day = '16';
	$limit2_month = date("m",mktime(0, 0, 0, $limit1_month +1 , $limit1_day, $limit1_year));
	$limit2_day = '0';
	$limit2_year = $limit1_month==12 ? $limit1_year+1 : $limit1_year;
	$select2 = " selected";
}

// 全月指定
if($_POST['m_half'] == "3"){
	$limit1_day = '01';
	$limit2_month = date("m",mktime(0, 0, 0, $limit1_month +1 , $limit1_day, $limit1_year));
	$limit2_day = '0';
	$limit2_year = $limit1_month==12 ? $limit1_year+1 : $limit1_year;
	$select3 = " selected";
}
$limit1 = $limit1_year.'-'.$limit1_month.'-'.$limit1_day;
$limit2 = $limit2_year.'-'.$limit2_month.'-'.$limit2_day;
$sql_limit_customer = " and customer.receipt_date >= '".$limit1."' AND customer.receipt_date <= '".$limit2."'";
$sql_limit_exam 	= " and exam.visit_date >= '".$limit1."' AND exam.visit_date <= '".$limit2."'";

// 絞り検索条件の設定-----------------------------------------------------

$dWhere = "";
if( $_REQUEST['age'] ) {
	$age1 = $_REQUEST['age'] * 10 ;
	$age2 = ($_REQUEST['age']+1) * 10 ;
	$dWhere .= " AND (YEAR(CURDATE())-YEAR(customer.birthday))>=".$age1." and (YEAR(CURDATE())-YEAR(customer.birthday))<".$age2;
	$serach_name1 .= "/".$gAge[$_REQUEST['age']];
}

if( $_REQUEST['job'] ) {
	$dWhere .= " AND customer.job = '".addslashes($_REQUEST['job'])."'";
	$serach_name1 .= "/".$gJob2[$_REQUEST['job']];
}

if( $_REQUEST['prefecture'] ) {
	$dWhere .= " AND customer.prefecture = '".addslashes($_REQUEST['prefecture'])."'";
	$serach_name1 .= "/".$gPref[$_REQUEST['prefecture']];
}

if( $_REQUEST['ad_name'] ) {
	$dWhere .= " AND customer.ad_name = '".addslashes($_REQUEST['ad_name'])."'";
	$serach_name1 .= "/".$gAd_name[$_REQUEST['ad_name']];
}

if( $_REQUEST['weather'] ) {
	$dWhere .= " AND customer.weather = '".addslashes($_REQUEST['weather'])."'";
	$serach_name1 .= "/".$gWeather[$_REQUEST['weather']];
}

if( $_REQUEST['diagnosis_type'] ) {
	$dWhere2 .= " AND exam.diagnosis_type = '".addslashes($_REQUEST['diagnosis_type'])."'";
	$serach_name2 .= "/".$gDiagnosis_type[$_REQUEST['diagnosis_type']];
}

if( $_REQUEST['diagnosis_content'] ) {
	$dWhere2 .= " AND exam.diagnosis_content = '".addslashes($_REQUEST['diagnosis_content'])."'";
	$serach_name2 .= "/".$gDiagnosis_content[$_REQUEST['diagnosis_content']];
}

if( $_REQUEST['sales_zone'] ) {
	$sql_sum = "sum(exam.amount_cash)+sum(exam.amount_card)+sum(exam.amount_loan)";
	$dWhere2 = " having (";

	if($_REQUEST['sales_zone'] == 1) $dWhere2 .= $sql_sum . "  < 10000" ;
	if($_REQUEST['sales_zone'] == 2) $dWhere2 .= $sql_sum ."  >= 10000 and ".$sql_sum." <  50000";
	if($_REQUEST['sales_zone'] == 3) $dWhere2 .= $sql_sum ."  >= 50000 and ".$sql_sum." < 100000";
	if($_REQUEST['sales_zone'] == 4) $dWhere2 .= $sql_sum ." >= 100000 and ".$sql_sum." < 500000";
	if($_REQUEST['sales_zone'] == 5) $dWhere2 .= $sql_sum .">= 1000000"  ;
	$dWhere2 .= ")";
	$serach_name2 .= "/".$gSalesZone[$_REQUEST['sales_zone']];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$data = array();
$total = array();
$sell = array();
$total_sell = array();
$customer_info = (isset($_POST['customer_info'])) ? implode(",",$_POST['customer_info']) : ((isset($_POST['diagnosis_info'])) ? "" : "年代");
$diagnosis_info = (isset($_POST['diagnosis_info'])) ? implode(",",$_POST['diagnosis_info']) : ((isset($_POST['customer_info'])) ? "" : "診察分類");
$sql_sum_sell = "sum(amount_cash)+sum(amount_card)+sum(amount_loan) as sum_sell ";
$gCustomerAnalytics = array(
		 "年代" 		=> array( "name" => "customer.birthday" , 		"select" => $customer_info ,"table" => "customer" , "item" => $gAge ,				"sql_limit" =>$sql_limit_customer,"condition" =>"") ,
		 "性別" 		=> array( "name" => "customer.sex" , 			"select" => $customer_info ,"table" => "customer" , "item" => $gSex ,				"sql_limit" =>$sql_limit_customer) ,
		 "職業" 		=> array( "name" => "customer.job" , 			"select" => $customer_info ,"table" => "customer" , "item" => $gJob2 ,				"sql_limit" =>$sql_limit_customer) ,
		 "地域" 		=> array( "name" => "customer.prefecture" , 	"select" => $customer_info ,"table" => "customer" , "item" => $gPref ,				"sql_limit" =>$sql_limit_customer) ,
		 "婚姻" 		=> array( "name" => "customer.marriage" , 		"select" => $customer_info ,"table" => "customer" , "item" => $gMarriage2 ,			"sql_limit" =>$sql_limit_customer) ,
		 "媒体" 		=> array( "name" => "customer.ad_name" , 		"select" => $customer_info ,"table" => "customer" , "item" => $gAd_name ,			"sql_limit" =>$sql_limit_customer) ,
		 "天気" 		=> array( "name" => "customer.weather" , 		"select" => $customer_info ,"table" => "customer" , "item" => $gWeather  ,			"sql_limit" =>$sql_limit_customer) ,
		 "受付日付" 	=> array( "name" => "customer.receipt_date" , 	"select" => $customer_info ,"table" => "customer" , "item" => "" ,					"sql_limit" =>$sql_limit_customer,"condition" =>"") ,

		 "診察分類" 	=> array( "name" => "exam.diagnosis_type" , 	"select" => $diagnosis_info ,"table" => "exam" , 	"item" => $gDiagnosis_type ,	"sql_limit" =>$sql_limit_exam) ,
		 "治療内容" 	=> array( "name" => "exam.diagnosis_content",	"select" => $diagnosis_info ,"table" => "exam" , 	"item" => $gDiagnosis_content ,	"sql_limit" =>$sql_limit_exam) ,
		 "売上/日"	=> array( "name" => "exam.visit_date" , 		"select" => $diagnosis_info ,"table" => "exam" , 	"item" => "" ,					"sql_limit" =>$sql_limit_exam,"condition" =>"") ,
		 "総売上/人"	=> array( "name" => "exam.total" , 				"select" => $diagnosis_info ,"table" => "exam" , 	"item" => "" ,					"sql_limit" =>$sql_limit_exam,"condition" =>"") ,
		 "月間売上"	=> array( "name" => "exam.visit_date" , 		"select" => $diagnosis_info ,"table" => "exam" , 	"item" => "" ,					"sql_limit" =>$sql_limit_exam,"condition" =>"") ,
		 "来院日" 	=> array( "name" => "exam.visit_date" , 		"select" => $diagnosis_info ,"table" => "exam" , 	"item" => "" ,					"sql_limit" =>$sql_limit_exam,"condition" =>"") 
		);

foreach($gCustomerAnalytics as $keys => $vals){
	if (ereg($keys, $vals['select'])) {
		switch ($keys) {
			case '受付日付':
			case '来院日':
				$max[$keys] = 0;
				$colum_sell = ($keys=="来院日")? ",".$sql_sum_sell : "";
				if($vals['table']=="exam") $dWhere = $dWhere2;
				$sql = "SELECT ".$vals['name']." as date,count(".$vals['name'].") as cnt ".$colum_sell." FROM ".$vals['table']." WHERE id>0 ".$vals['sql_limit']. $dWhere." GROUP BY ".$vals['name']." ORDER BY ".$vals['name'] ;
				
				$result = $GLOBALS['mysqldb']->query( $sql ) or die('query error'.$GLOBALS['mysqldb']->error);
				if($result->num_rows >= 1){
					while ( $list = $result->fetch_assoc() ) {
						if($list['cnt']){
							$data[$keys][$list['date']] = $list['cnt'];
							$total[$keys] += $data[$keys][$list['date']];
							if($max[$keys] <$list['cnt'])$max[$keys]  = $list['cnt'];

							$sell[$keys][$list['date']] = $list['sum_sell'];
							$total_sell[$keys] += $sell[$keys][$list['date']];
						}
					}
				}
				break;
			case '売上/日':
				$max[$keys] = 0;
				$sql = "SELECT ".$vals['name']." as date,sum(amount_cash)+sum(amount_card)+sum(amount_loan) as sum_daily FROM ".$vals['table']." WHERE id>0 ".$vals['sql_limit'].$dWhere2." GROUP BY ".$vals['name']." ORDER BY ".$vals['name'] ;
				$result = $GLOBALS['mysqldb']->query( $sql ) or die('query error'.$GLOBALS['mysqldb']->error);
				if($result->num_rows >= 1){
					while ( $list = $result->fetch_assoc() ) {
						if($list['sum_daily']){
							$data[$keys][$list['date']] = $list['sum_daily'];
							$total[$keys] += $data[$keys][$list['date']];
							if($max[$keys] <$list['sum_daily'])$max[$keys]  = $list['sum_daily'];

							$sell[$keys][$list['date']] = $list['sum_daily'];
							$total_sell[$keys] += $sell[$keys][$list['date']];
						}
					}
				}
				break;
			case '月間売上':
				$max[$keys] = 0;
				$sql = "SELECT DATE_FORMAT(".$vals['name'].",'%Y-%m') as date,sum(amount_cash)+sum(amount_card)+sum(amount_loan) as sum_monthly FROM ".$vals['table']." WHERE id>0 ".$vals['sql_limit'].$dWhere2." GROUP BY DATE_FORMAT(".$vals['name'].",'%Y-%m') ORDER BY ".$vals['name'] ;
				$result = $GLOBALS['mysqldb']->query( $sql ) or die('query error'.$GLOBALS['mysqldb']->error);
				if($result->num_rows >= 1){
					while ( $list = $result->fetch_assoc() ) {
						if($list['sum_monthly']){
							$data[$keys][$list['date']] = $list['sum_monthly'];
							$total[$keys] += $data[$keys][$list['date']];
							if($max[$keys] <$list['sum_monthly'])$max[$keys]  = $list['sum_monthly'];

							$sell[$keys][$list['date']] = $list['sum_monthly'];
							$total_sell[$keys] += $sell[$keys][$list['date']];
						}
					}
				}
				break;
			default:
				$max[$keys] = 0;
				foreach($vals['item'] as $key => $val){
					//$sql = "SELECT count(".$vals['name'].") as cnt FROM ".$vals['table']." WHERE  ".$vals['name']."='".$key."' ".$vals['sql_limit']. " ORDER BY ".$vals['name'] ;
					if($keys=='年代'){
						if($key){
							$age1 = $key * 10 ;	$age2 = ($key+1) * 10 ;
							$sql_age = "(YEAR(CURDATE())-YEAR(".$vals['name']."))>=".$age1." and (YEAR(CURDATE())-YEAR(".$vals['name']."))<".$age2;
						}else $sql_age = "(YEAR(CURDATE())-YEAR(".$vals['name']."))<20 or ".$vals['name']."=''";
					}else $sql_age = $vals['name']."='".$key."' ";
					if($vals['table']=="exam") $dWhere = $dWhere2;
					$sql = "SELECT id FROM ".$vals['table']." WHERE  ".$sql_age.$vals['sql_limit'].$dWhere. " ORDER BY ".$vals['name'] ;
					//var_dump($sql);
					$result = $GLOBALS['mysqldb']->query( $sql ) or die('query error'.$GLOBALS['mysqldb']->error);
					$cnt = $result->num_rows;

					if($cnt >=1 ) {
						$data[$keys][$val] = $cnt;
						$total[$keys] += $data[$keys][$val];
						if($max[$keys] <$cnt)$max[$keys]  = $cnt;

						$i = 1;
						$examWhere = "WHERE customer_id in (";
						while ( $list = $result->fetch_assoc() ) {
							$or = ($i<$cnt ) ? "," : "";
							$examWhere .= $list['id'].$or;
							$i++;
						}
						$examWhere .= ")";
						//売上合計
						$sql = "SELECT " .$sql_sum_sell . " FROM exam  ".$examWhere;
						//var_dump($sql);exit;
						$sell[$keys][$val] = Get_Result_Sql_Col($sql );
						$total_sell[$keys] += $sell[$keys][$val];
					}
				}
				break;
		}
		
	}
}
if(!$_POST['graph_no']) $_POST['graph_no'] = 2;
?>