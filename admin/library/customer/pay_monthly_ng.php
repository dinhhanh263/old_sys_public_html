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

$ym_from=$_POST['ym_from']=$_POST['ym_from'] ? $_POST['ym_from'] : date("Y/m",strtotime(date('Y/m/01') . "-3 month"));
$ym_to=$_POST['ym_to']=$_POST['ym_to'] ? $_POST['ym_to'] : date("Y/m");

$pre_ym = date("Y/m", strtotime($_POST['ym_to']."/01 -1month"));
$next_ym = date("Y/m", strtotime($_POST['ym_to']."/01 +1month"));


// 表示ページ設定-----------------------------------------------------
$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定-----------------------------------------------------
$dWhere = "";
$keyword = addslashes($_POST['keyword']);
if( $keyword != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($keyword,"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($keyword,"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.mail LIKE '%".trim( $keyword )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($keyword,"aSKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".trim( str_replace("-","",mb_convert_kana($keyword,"as", "UTF-8")) )."%'";
	$dWhere .= " ) ";
}
//$dWhere .= " AND  cr.contract_date>='".$_POST['contract_date']."'";
//$dWhere .= " AND  cr.contract_date<='".$_POST['contract_date2']."'";
$dWhere .= " AND DATE_FORMAT(cr.contract_date, '%Y/%m')>='".addslashes($ym_from)."'";
$dWhere .= " AND DATE_FORMAT(cr.contract_date, '%Y/%m')<='".addslashes($ym_to)."'";

if($_POST['search_shop_id'] !=0) $dWhere .= " AND  cr.shop_id='".addslashes($_POST['search_shop_id'])."'";
if($_POST['status'] ) $dWhere .= " AND cr.status = '".addslashes($_POST['status'])."'";

// 月額コースID取得
if( $_POST['course']==1 ) $dWhere .= " AND cs.del_flg=0 and cs.new_flg=0"; // 旧月額
if( $_POST['course']==3 ) $dWhere .= " AND cs.del_flg=0 and cs.new_flg=1"; // 新月額

//払う必要のある月取得
$mustPaysListDiff = [];
$mustPaysList = [];
getMustPaysList($mustPaysListDiff, $mustPaysList, $gContractStatus, $dWhere);
//払った月取得
$salesListDiff = [];
$salesList = [];
getSalesList($salesListDiff, $salesList, $gContractStatus, $dWhere);


//print_r($mustPaysListDiff);
//差分を求め未払い月取得
$array_diff = array_udiff($mustPaysListDiff, $salesListDiff, function ($array1, $array2) {
//    var_dump($array1, $array2);
    $result1 = $array1[0] - $array2[0];
    $result2 = $array1[1] - $array2[1];
    $result3 = $array1[2] - $array2[2];
    return $result1 + $result2 + $result3;
});
//print_r($array_diff);
//exit;
//未払い月情報取得
$unpaidList = getUnpaidInfo($array_diff, $mustPaysList);
//print_r($unpaidList);


//過払い月取得
$overUniqueList = getOverPayMonth($salesListDiff);
//print_r($salesListDiff);
//exit;

//過払い月情報取得
$overPayList = getOverPayInfo($overUniqueList, $salesList);

//$balanceMinusList = getBalanceMinusList($gContractStatus, $dWhere);
$balanceMinusList = array();
//var_dump($overPayList);
//var_dump($balanceMinusList);
//exit;



$list = array_merge($unpaidList, $overPayList, $balanceMinusList);
//var_dump($balanceMinusList);
//$list = array_merge($unpaidList, $overPayList, $balanceMinusList);
$dGet_Cnt = count($list);
$list = array_slice($list, $dStart, $dLine_Max);

//店舗リスト-----------------------------------------------------
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);


//未払い月情報取得
function getOverPayInfo($overUniqueList, $salesList) {
    $overPayList = []; 
    foreach($overUniqueList as $unique_datas) { 
        $overPayList[] = $salesList[$unique_datas[0]][$unique_datas[1]][$unique_datas[2]];
    }

    return $overPayList;
}

//過払い月取得
function getOverPayMonth($salesListDiff) {
    $tmpContractId = "";
    $tmpCustomerId = "";
    $tmpYm = "";
    $uniqueList = [];
    foreach($salesListDiff as $row) {
        if($row[0] == $tmpContractId && $row[1] == $tmpCustomerId && $row[2] == $tmpYm) {
            $uniqueList[] = $row;
        }
        $tmpContractId = $row[0];
        $tmpCustomerId = $row[1];
        $tmpYm = $row[2];
    }
    return $uniqueList;
}
//未払い月情報取得
function getUnpaidInfo($array_diff, $mustPaysList) {
    $unpaidList = []; 
    foreach($array_diff as $diff_datas) {
        $unpaidList[] = $mustPaysList[$diff_datas[0]][$diff_datas[1]][$diff_datas[2]];
    }
    return $unpaidList;
}

//払う必要のある月取得
function getMustPaysList(&$msutListDiff, &$msutList, $gContractStatus, $dWhere) {
    $dSql = $GLOBALS['mysqldb']->query("SELECT c.id as customer_id, c.no, c.name_kana,c.tel, cr.id as contract_id, cr.start_ym + cs.times as pay_start_ym, cr.status, 
                                cs.name as course_name, CAST(DATE_FORMAT(NOW(), '%Y%m') as SIGNED) as now_ym,cr.contract_date
                        FROM customer as c
                                INNER JOIN contract cr ON cr.customer_id = c.id
                                INNER JOIN course cs ON cr.course_id = cs.id
                        WHERE c.del_flg=0
                                AND cr.del_flg=0
                                AND cr.start_ym<>0
                                AND cr.balance<=0
                                AND cr.status=0
                                AND cs.type=1" .$dWhere.
                        " ORDER BY customer_id, contract_id
                        "
    ) or die('query error'.mysql_error());

    if ($dSql) {
        while ($row =  $dSql->fetch_assoc() ) {
            $pay_start_m = $row['pay_start_ym'];
            $now_m = $row['now_ym'];
            for($i=$pay_start_m; $i <= $now_m+1; $i++) {    //一か月先まで取得
                $ym = (int)$i;
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['customer_id'] = $row['customer_id'];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['ng'] = '未払い';
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['no'] = $row['no'];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['name'] = $row['name'];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['name_kana'] = $row['name_kana'];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['tel'] = $row['tel'];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['status'] = $gContractStatus[$row['status']];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['course_name'] = $row['course_name'];
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['pay_amount'] = "";
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['pay_date'] = "";
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['option_year'] = substr($i, 0, 4);
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['option_month'] = (int)substr($i, 4, 2);
                $msutList[$row['contract_id']][$row['customer_id']][$ym]['contract_date'] = $row['contract_date'];

                $tempList = [$row['contract_id'], $row['customer_id'], $ym]; 
                $msutListDiff[]  = $tempList;
            }
        }
    }
    return $msutListDiff;
}

//払う必要のある月取得
function getSalesList(&$salesListDiff, &$salesList, $gContractStatus, $dWhere) {
    $dSql = $GLOBALS['mysqldb']->query("SELECT sl.*, c.no as no, c.name_kana as name_kana,c.tel as tel, cs.type, cs.name as course_name, cs.new_flg, cr.start_ym, 
                        cr.cancel_date, cr.status, (option_price + option_transfer + option_card) as pay_amount,
                        (cr.start_ym + cs.times) as pay_start_ym,cr.contract_date as contract_date
                        FROM sales as sl
                        INNER JOIN customer c ON sl.customer_id = c.id
                        INNER JOIN contract cr ON sl.contract_id = cr.id
                        INNER JOIN course cs ON sl.course_id = cs.id 
                        WHERE sl.del_flg=0 
                        AND sl.option_name=4
                        AND cr.status=0
                        AND cs.type=1" .$dWhere.
                        " ORDER BY customer_id, contract_id, option_year, option_month"
    ) or die('query error'.mysql_error());

    if ($dSql) {
        while ( $row = $dSql->fetch_assoc() ) {
            if(strpos($row['option_month'], ',') === FALSE) {
                $ym = (int)($row['option_year']. sprintf("%02d", $row['option_month']));
                if($row['pay_start_ym'] >= $ym) {
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['customer_id'] = $row['customer_id'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['ng'] = '過払い';
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['no'] = $row['no'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['name_kana'] = $row['name_kana'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['tel'] = $row['tel'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['status'] = $gContractStatus[$row['status']];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['course_name'] = $row['course_name'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['pay_amount'] = $row['pay_amount'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['pay_date'] = $row['pay_date'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['option_year'] = $row['option_year'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['option_month'] = $row['option_month'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['contract_date'] = $row['contract_date'];
                    $salesList[$row['contract_id']][$row['customer_id']][$ym]['pay_start_ym'] = $row['pay_start_ym'];

                    $tempList = [$row['contract_id'], $row['customer_id'], $ym];
                    $salesListDiff[]  = $tempList;
                }
            } else {
                $option_months = explode (',', $row['option_month']);
                foreach($option_months as $val=>$month){    
                    $ym = (int)($row['option_year']. sprintf("%02d", $month));
                    if($row['pay_start_ym'] >= $ym) {
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['customer_id'] = $row['customer_id'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['ng'] = '過払い';
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['no'] = $row['no'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['name_kana'] = $row['name_kana'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['tel'] = $row['tel'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['status'] = $gContractStatus[$row['status']];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['course_name'] = $row['course_name'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['pay_amount'] = $row['pay_amount'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['pay_date'] = $row['pay_date'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['option_year'] = $row['option_year'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['option_month'] = $month;
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['contract_date'] = $row['contract_date'];
                        $salesList[$row['contract_id']][$row['customer_id']][$ym]['pay_start_ym'] = $row['pay_start_ym'];
                        $tempList = [$row['contract_id'], $row['customer_id'], $ym];
                        $salesListDiff[]  = $tempList;
                    }
                }
            }
        }
    }
    return $salesListDiff;
}
//売掛金マイナス
function getBalanceMinusList($gContractStatus, $dWhere) {
    $balanceMinusList = [];
    $dSql = $GLOBALS['mysqldb']->query("SELECT c.id as customer_id, c.no, c.name_kana,c.tel, cr.id as contract_id, cr.start_ym + cs.times as pay_start_ym, cr.status, 
                                cs.name as course_name, CAST(DATE_FORMAT(NOW(), '%Y%m') as SIGNED) as now_ym, cr.balance, cr.contract_date
                        FROM customer as c
                                INNER JOIN contract cr ON cr.customer_id = c.id
                                INNER JOIN course cs ON cr.course_id = cs.id
                        WHERE c.del_flg=0
                                AND cr.del_flg=0
                                AND cr.start_ym<>0
                                AND cr.balance<0 AND c.ctype =1 AND cr.chk_flg =0
                                AND cr.status=0
                                AND cs.type=1" .$dWhere.
                        " ORDER BY customer_id, contract_id
                        "
    ) or die('query error'.mysql_error());

    if ($dSql) {
        while ($row = mysql_fetch_array($dSql, MYSQL_ASSOC)) {
            $tempList['customer_id'] = $row['customer_id'];
            $tempList['ng'] = '過払い（売掛金マイナス）';
            $tempList['no'] = $row['no'];
            $tempList['name_kana'] = $row['name_kana'];
            $tempList['tel'] = $row['tel'];
            $tempList['status'] = $gContractStatus[$row['status']];
            $tempList['course_name'] = $row['course_name'];
            $tempList['pay_amount'] = $row['balance'];
            $tempList['pay_date'] = "";
            $tempList['option_year'] = "";
            $tempList['option_month'] = "";
            $tempList['contract_date'] = $row['contract_date'];
            $balanceMinusList[] = $tempList;
        }
    }
    return $balanceMinusList;
}


?>