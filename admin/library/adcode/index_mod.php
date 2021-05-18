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

$_POST['access_date']=$_POST['access_date'] ? $_POST['access_date'] : date("Y-m-d");
$_POST['access_date2']=$_POST['access_date2'] ? $_POST['access_date2'] : ($_POST['access_date'] ? $_POST['access_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['access_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['access_date']." +1day"));

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
    $rtn = $GLOBALS['mysqldb']->query("SELECT adcode,count(id) as cnt FROM " . $table . " WHERE reg_date >= '" . $_POST['access_date'] . "' AND reg_date <= '" . $_POST['access_date2'] ."' and adcode !=''".$dWhere." GROUP BY adcode ORDER BY adcode") or die('query error' . $GLOBALS['mysqldb']->error);

	$data['total_cv_cnt'] = 0;
    if ($rtn->num_rows >= 1) {
        $i=0;
        while ($list = $rtn->fetch_assoc()) {
            $adcode = $GLOBALS['mysqldb']->query("SELECT adcode.*,agent.name as agent FROM adcode,agent where adcode.agent_id=agent.id and adcode.job_flg=0 and adcode.id ='" . $list['adcode'] . "'") or die('query error' . $GLOBALS['mysqldb']->error);
			$adcodeInfo = $adcode->fetch_assoc();
			$data[$i]['reg_all'] = $list['cnt'];
			$data[$i]['adcode'] = $adcodeInfo['adcode'];
			$data[$i]['name'] = $adcodeInfo['name'];
			$data[$i]['agent'] = $adcodeInfo['agent'];
			$data['total_cv_cnt'] += $list['cnt'];
			$i++;
        }
    }
}

?>