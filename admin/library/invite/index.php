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

// 権限設定------------------------------------------------------------------------
#if($authority_level >= 2 && $authority_level <= 7 || $authority_level == 22){ //店長権限かスタッフ（契約）権限
if($authority_level >= 2 && $authority_level <= 7 || $authority_level == 17 || $authority_level == 22){ //店長権限かスタッフ（契約）権限
  $authority_view = 'cc_staff';
}else if($authority_level < 2){ // 本社権限以上
  $authority_view = 'vielis';
}else{
  return;
}

session_start();

$table = "introducer";

$_POST['search_contract_date_rb'] = $_POST['search_contract_date_rb']? $_POST['search_contract_date_rb'] : '';
$_POST['search_contract_date_ra'] = $_POST['search_contract_date_ra']? $_POST['search_contract_date_ra'] : (($_GET['search_contract_date_rb'])? $_POST['search_contract_date_rb'] :'');
$_POST['search_refund_date_rb'] = $_POST['search_refund_date_rb']? $_POST['search_refund_date_rb'] : '';
$_POST['search_refund_date_ra'] = $_POST['search_refund_date_ra']? $_POST['search_refund_date_ra'] : (($_GET['search_refund_date_rb'])? $_POST['search_refund_date_rb'] :'');
$_POST['search_refund_request_rb'] = $_POST['search_refund_request_rb']? $_POST['search_refund_request_rb'] : '';
$_POST['search_refund_request_ra'] = $_POST['search_refund_request_ra']? $_POST['search_refund_request_ra'] : (($_GET['search_refund_request_rb'])? $_POST['search_refund_request_rb'] :'');
$_POST['search_refund_contact_rb'] = $_POST['search_refund_contact_rb']? $_POST['search_refund_contact_rb'] : '';
$_POST['search_refund_contact_ra'] = $_POST['search_refund_contact_ra']? $_POST['search_refund_contact_ra'] : (($_GET['search_refund_contact_rb'])? $_POST['search_refund_contact_rb'] :'');

$pre_date['contract_date'] = date("Y-m-d", strtotime($_POST['search_contract_date_rb']." -1day"));
$next_date['contract_date'] = date("Y-m-d", strtotime($_POST['search_contract_date_rb']." +1day"));
$pre_date['refund_request'] = date("Y-m-d", strtotime($_POST['search_refund_request_rb']." -1day"));
$next_date['refund_request'] = date("Y-m-d", strtotime($_POST['search_refund_request_rb']." +1day"));
$pre_date['refund_date'] = date("Y-m-d", strtotime($_POST['search_refund_date_rb']." -1day"));
$next_date['refund_date'] = date("Y-m-d", strtotime($_POST['search_refund_date_rb']." +1day"));
$pre_date['refund_contact'] = date("Y-m-d", strtotime($_POST['search_refund_contact_rb']." -1day"));
$next_date['refund_contact'] = date("Y-m-d", strtotime($_POST['search_refund_contact_rb']." +1day"));

// データの仮削除-----------------------------------------------------
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
  $sql = "UPDATE " . $table . " SET del_flg = 1,edit_date='" . date('Y-m-d H:i:s') . "'";
  $sql .= " WHERE id = '" . addslashes($_REQUEST['id']) . "'";
  $dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
  if ($dRes) {
    header( "Location: index.php" );
  }
}

// ラベル------------------------------------------------------------------------

$labelBankState = array('未確認', '有効', '無効');
$labelRefundScope = array('対象外', '対象');

// コース名リスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}
// 返金額リスト
$refund_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM refund WHERE del_flg = 0" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result3 = $refund_sql->fetch_assoc() ) {
	$refund_list[$result3['cource_id']] = $result3['money'];
}

// 表示ページ設定------------------------------------------------------------------------


$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 20;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
  $dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

// 初期化
$dWhere = "";

  // 検索＞紹介先／会員番号・名前・名前（カナ）
  $searchSakiCustomer = "";
  if ($_POST['search_saki_customer_no'] != "") $searchSakiCustomer .= " AND ( saki_no LIKE '%" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_saki_customer_no']) . "%'";
  if ($_POST['search_saki_customer_name'] != "") {
    $_operator = ($searchSakiCustomer != "")? ' AND' : ' AND ( ';
    $searchSakiCustomer .=  $_operator . " replace(saki_name, '　', '') LIKE '%" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_saki_customer_name']) . "%'";
  }
  if ($_POST['search_saki_customer_kana'] != "") {
    $_operator = ($searchSakiCustomer != "")? ' AND' : ' AND ( ';
    $searchSakiCustomer .=  $_operator . " replace(saki_name_kana, '　', '') LIKE '%" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_saki_customer_kana']) . "%'";
  }
  if ($searchSakiCustomer != "") $searchSakiCustomer .= " )";
  $dWhere .= $searchSakiCustomer;

  // 検索＞紹介元／会員番号・名前・名前（カナ）
  $searchMotoCustomer = "";
  if ($_POST['search_moto_customer_no'] != "") $searchMotoCustomer .= " AND ( moto_no LIKE '%" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_moto_customer_no']) . "%'";
  if ($_POST['search_moto_customer_name'] != "") {
    $_operator = ($searchMotoCustomer != "")? ' AND' : ' AND ( ';
    $searchMotoCustomer .= $_operator . " replace(moto_name, '　', '') LIKE '%" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_moto_customer_name']) . "%'";
  }
  if ($_POST['search_moto_customer_kana'] != "") {
    $_operator = ($searchMotoCustomer != "")? ' AND' : ' AND ( ';
    $searchMotoCustomer .= $_operator . " replace(moto_name_kana, '　', '') LIKE '%" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_moto_customer_kana']) . "%'";
  }
  if ($searchMotoCustomer != "") $searchMotoCustomer .= " )";
  $dWhere .= $searchMotoCustomer;

  // 検索＞口座状況
  $searchMotoBank = "";
  if ($_POST['search_bank_state'] != "") {
    $isNull ="";
    $targets = rtrim(str_replace('-', '', implode(",", $_POST['search_bank_state'])), ',');
    if (in_array('-', $_POST['search_bank_state'])) {
      $_ope = ($targets == "")? ' AND ' : ' OR ';
      $isNull = $_ope . "moto_bank_status IS NULL";
    }
    if ($targets != "") {
      $searchMotoBank .= " AND (moto_bank_status IN ( ";
      $searchMotoBank .= $targets;
      $searchMotoBank .= " )" . $isNull . ")";
    } else {
      $searchMotoBank .= $isNull;
    }
  }
  $dWhere .= $searchMotoBank;

  // 検索＞返金対象
  $searchRefundScope = "";
  if ($_POST['search_refund_scope'] != "") {
    for ($i = 0; $i < count($_POST['search_refund_scope']); $i++) {
      if ($_POST['search_refund_scope'][$i] == 0) {
        $searchRefundScope .= "( moto_contract_r_times > 0 AND moto_contract_status NOT IN ( 1, 2, 3, 6 ) AND saki_contract_r_times > 0 AND saki_contract_status NOT IN ( 1, 2, 3, 6 ) )";
      } elseif ($_POST['search_refund_scope'][$i] == 1) {
        $_ope = ($searchRefundScope != "")? ' OR ' : '';
        $searchRefundScope .= $_ope . "( (moto_contract_r_times = 0 OR saki_contract_r_times = 0) AND moto_contract_status NOT IN ( 1, 2, 3, 6 ) AND saki_contract_status NOT IN ( 1, 2, 3, 6 ) )";
      } elseif ($_POST['search_refund_scope'][$i] == 2) {
        $_ope = ($searchRefundScope != "")? ' OR ' : '';
        $searchRefundScope .= $_ope . "( moto_contract_r_times IS NULL OR saki_contract_r_times IS NULL OR moto_contract_status IS NULL OR saki_contract_status IS NULL OR moto_contract_status IN ( 1, 2, 3, 6 ) OR saki_contract_status IN ( 1, 2, 3, 6 ) )";
      }
    }
  }
  $dWhere .= ($searchRefundScope != "")? ' AND ( ' . $searchRefundScope . ' )' : "";

  // 検索＞紹介先契約日
  $searchSakiContractDate = "";
  if ($_POST['search_contract_date'] == 'all') {
    $_GET['search_contract_date_rb'] = $_POST['search_contract_date_rb'] = $_POST['search_contract_date_ra'] = null;
  } elseif ($_POST['search_contract_date'] =='done') {
    if ($_POST['search_contract_date_rb'] != "")
      $searchSakiContractDate .= " AND (saki_contract_date >= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_contract_date_rb']) . "')";
    if ($_POST['search_contract_date_ra'] != "")
      $searchSakiContractDate .= " AND (saki_contract_date <= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_contract_date_ra']) . "')";
  } elseif ($_POST['search_contract_date'] =='yet') {
    $_GET['search_contract_date_rb'] = $_POST['search_contract_date_rb'] = $_POST['search_contract_date_ra'] = null;
    $searchSakiContractDate .= " AND (saki_contract_date = '0000-00-00' OR saki_contract_date IS NULL)";
  }

  $dWhere .= $searchSakiContractDate;

  // 検索＞申請連絡受付日
  $searchRefundRequest = "";

  if ($_POST['search_refund_request'] == 'all') {
    $_GET['search_refund_request_rb'] = $_POST['search_refund_request_rb'] = $_POST['search_refund_request_ra'] = null;
  } elseif ($_POST['search_refund_request'] == 'done') {
    if ($_POST['search_refund_request_rb'] != "")
      $searchRefundRequest .= " AND i.refund_request >= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_refund_request_rb']) . "'";
    if ($_POST['search_refund_request_ra'] != "")
      $searchRefundRequest .= " AND i.refund_request <= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_refund_request_ra']) . "' AND i.refund_request > '0000-00-00'";
  } elseif ($_POST['search_refund_request'] == 'yet') {
    $_GET['search_refund_request_rb'] = $_POST['search_refund_request_rb'] = $_POST['search_refund_request_ra'] = null;
    $searchRefundRequest .= " AND (i.refund_request = '0000-00-00' OR i.refund_request IS NULL)";
  }

  $dWhere .= $searchRefundRequest;

  // 検索＞返金日
  $searchRefundDate = "";
  if ($_POST['search_refund_date'] == 'all') {
    $_GET['search_refund_date_rb'] = $_POST['search_refund_date_rb'] = $_POST['search_refund_date_ra'] = null;

  } elseif ($_POST['search_refund_date'] == 'done') {
    if ($_POST['search_refund_date_rb'] != "")
      $searchRefundDate .= " AND i.refund_date >= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_refund_date_rb']) . "'";
    if ($_POST['search_refund_date_ra'] != "")
      $searchRefundDate .= " AND i.refund_date <= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_refund_date_ra']) . "' AND i.refund_date > '0000-00-00'";
  } elseif ($_POST['search_refund_date'] == 'yet') {
    $_GET['search_refund_date_rb'] = $_POST['search_refund_date_rb'] = $_POST['search_refund_date_ra'] = null;
    $searchRefundDate .= " AND (i.refund_date = '0000-00-00' OR i.refund_date IS NULL)";
  }

  $dWhere .= $searchRefundDate;

  // 検索＞返金連絡日
  $searchRefundContact = "";
  if ($_POST['search_refund_contact'] == 'all') {
    $_GET['search_refund_contact_rb'] = $_POST['search_refund_contact_rb'] = $_POST['search_refund_contact_ra'] = null;

  } elseif ($_POST['search_refund_contact'] == 'done') {
    if ($_POST['search_refund_contact_rb'] != "")
      $searchRefundContact .= " AND i.refund_contact >= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_refund_contact_rb']) . "'";
    if ($_POST['search_refund_contact_ra'] != "")

      $searchRefundContact .= " AND i.refund_contact <= '" . $GLOBALS['mysqldb']->real_escape_string($_POST['search_refund_contact_ra']) . "' AND i.refund_contact > '0000-00-00'";
  } elseif ($_POST['search_refund_contact'] == 'yet') {
    $_GET['search_refund_contact_rb'] = $_POST['search_refund_contact_rb'] = $_POST['search_refund_contact_ra'] = null;
    $searchRefundContact .= " AND (i.refund_contact = '0000-00-00' OR i.refund_contact IS NULL)";
  }

  $dWhere .= $searchRefundContact;

// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM `introducer` WHERE del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error' . $GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$eSql = "SELECT
        count(i.id)
        FROM
          `introducer` i
            LEFT JOIN moto ON i.customer_id = moto.moto_id
            LEFT JOIN saki ON i.introducer_customer_id = saki.saki_id
        WHERE i.del_flg = 0 " . $dWhere . "";

$dRtn2 = $GLOBALS['mysqldb']->query( $eSql ) or die('query error'.$GLOBALS['mysqldb']->error);

$dGet_Cnt = $dRtn2->fetch_row()[0];

$sql = "SELECT
        i.*,
        moto.moto_no,
        moto.moto_name,
        moto.moto_name_kana,
        moto.moto_tel,
        moto.moto_mail,
        moto.moto_contract_status,
        moto.moto_contract_shop_id,
        moto.moto_contract_course_id,
        moto.moto_contract_latest_date,
        moto.moto_contract_r_times,
        moto.moto_contract_date,
        moto.moto_bank_name,
        moto.moto_bank_branch,
        moto.moto_bank_account_type,
        moto.moto_bank_account_no,
        moto.moto_bank_account_name,
        moto.moto_bank_status,
        saki.saki_no,
        saki.saki_name,
        saki.saki_name_kana,
        saki.saki_tel,
        saki.saki_mail,
        saki.saki_contract_status,
        saki.saki_contract_shop_id,
        saki.saki_contract_course_id,
        saki.saki_contract_latest_date,
        saki.saki_contract_r_times,
        saki.saki_contract_date
        FROM
          `introducer` i
            LEFT JOIN moto ON i.customer_id = moto.moto_id
            LEFT JOIN saki ON i.introducer_customer_id = saki.saki_id
        WHERE i.del_flg = 0" . $dWhere . " GROUP BY i.id ORDER BY saki.saki_contract_date DESC LIMIT " . $dStart . "," . $dLine_Max;

$excute = $GLOBALS['mysqldb']->query($sql) or die('query error' . $GLOBALS['mysqldb']->error);


?>

