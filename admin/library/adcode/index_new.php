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

// テーブル設定--------------------------------------------------------------------------

$table = "customer";

// 媒体表示----------------------------------------------------------------------------

if(!$_POST )$_POST['hide_flg'] =  0 ;

// 代理店リスト----------------------------------------------------------------------------
$agent = $GLOBALS['mysqldb']->query( "SELECT * FROM agent WHERE del_flg=0 and  pid='' ORDER BY name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $agent->fetch_assoc() ) {
	$gAgent[$list['id']] = $list['name'];
}

// 期間指定---------------------------------------------------------------------------

$_POST['access_date']=$_POST['access_date'] ? date("Y-m-d 00:00:00", strtotime($_POST['access_date'])) : date("Y-m-d 00:00:00");
$_POST['access_date2']=$_POST['access_date2'] ? date("Y-m-d 23:59:59", strtotime($_POST['access_date2'])) : ($_POST['access_date'] ? date("Y-m-d 23:59:59", strtotime($_POST['access_date'])) : date("Y-m-d 23:59:59"));

$pre_date = date("Y-m-d 00:00:00", strtotime($_POST['access_date']." -1day"));
$pre_date2 = date("Y-m-d 23:59:59", strtotime($_POST['access_date']." -1day"));
$next_date = date("Y-m-d 00:00:00", strtotime($_POST['access_date']." +1day"));
$next_date2 = date("Y-m-d 23:59:59", strtotime($_POST['access_date']." +1day"));

// 検索条件の設定----------------------------------------------------------------------------

if($_POST['agent_id'] != "" ){
	$dWhere .= " and adcode.agent_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['agent_id'])."'";
}
// if($_POST['type'] != "" ){
// 	$dWhere .= " and adcode.type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
// }

// 表示順設定----------------------------------------------------------------------------

// if($_REQUEST['sort']=='agent') 	$order = "agent.name" ;
// elseif($_REQUEST['sort']) 		$order = "adcode.". $_REQUEST['sort'];
// else 							$order = "agent.name,adcode.name" ;
// $order = " order by ".$order .$_REQUEST['seq'];

// データの取得----------------------------------------------------------------------------

if ($_REQUEST['mode']=="display") {
    $customer = $GLOBALS['mysqldb']->query("SELECT customer.adcode,count(customer.id) as cnt FROM " . $table . " JOIN adcode ON customer.adcode = adcode.id WHERE customer.reg_date >= '" . $_POST['access_date'] . "' AND customer.reg_date <= '" . $_POST['access_date2'] ."' and customer.ctype=1 and customer.adcode !='' and customer.del_flg =0".$dWhere." GROUP BY customer.adcode ORDER BY customer.adcode") or die('query error' . $GLOBALS['mysqldb']->error);
    $reservation = $GLOBALS['mysqldb']->query("SELECT r.adcode,count(r.id) as reserv_cnt FROM reservation r JOIN customer c ON r.customer_id = c.id and c.ctype=1 and c.del_flg=0 JOIN adcode ON r.adcode = adcode.id WHERE r.reg_date >= '" . $_POST['access_date'] . "' AND r.reg_date <= '" . $_POST['access_date2'] ."' and r.adcode !='' and r.del_flg =0".$dWhere." GROUP BY r.adcode ORDER BY r.adcode") or die('query error' . $GLOBALS['mysqldb']->error);

    while($array_c = mysqli_fetch_assoc($customer)){
        $data[$array_c['adcode']]['reg_all']=$array_c['cnt'];
        $data['total_cv_cnt']+=$array_c['cnt'];
    }

    while($array_r = mysqli_fetch_assoc($reservation)){
        $data[$array_r['adcode']]['reserv_reg_all']=$array_r['reserv_cnt'];
        $data['reserv_total_cv_cnt']+=$array_r['reserv_cnt'];
    }
}

?>