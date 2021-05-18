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

$table = "introducer";

if ($_POST['checks'] == null) {
  $_SESSION['error']['csv_export'] = "CSVを出力する対象の行に1つ以上チェックを入れてください。";
  header("location: index.php");
}

// ラベル------------------------------------------------------------------------

$labelBankState = array('未確認', '有効', '無効');
$labelBankAccountType = array( 1 => '普通', 2 => '当座', 3 => '貯蓄');
$labelShop = getDatalist("shop");
$refund_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM refund WHERE del_flg = 0" ) or die('query error' . $GLOBALS['mysqldb']->error);
while ( $result3 = $refund_sql->fetch_assoc() ) {
	$refund_list[$result3['cource_id']] = $result3['money'];
}


// 検索条件の設定-------------------------------------------------------------------

$dWhere = " AND i.id in (";
for ($i = 0; $i < count($_POST['checks']); $i++ ) {
  $dWhere.= $_POST['checks'][$i];
  if ($i !== count($_POST['checks']) - 1) $dWhere.= ',';
}
$dWhere.= ")";

// データの取得----------------------------------------------------------------------

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
        WHERE i.del_flg = 0" . $dWhere . " GROUP BY i.id ORDER BY saki.saki_contract_date DESC";
$result = $GLOBALS['mysqldb']->query( $sql );

// CSVエクスポート----------------------------------------------------------------------

$filename = "introducer_list.csv";

header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=" . $filename);

if ( $result->num_rows >= 1 ) {

  $csv_header = "紹介先契約日,紹介元契約店舗名,紹介元会員番号,紹介元名前,銀行名,支店名,口座種別,口座番号,口座名義,返金額,申請受付連絡日,紹介先会員番号,紹介先名前,初回消化日,口座状況,返金対象\n";
  echo mb_convert_encoding($csv_header, "SJIS-win", "UTF-8");

  while ( $row = $result->fetch_assoc() ) {

    // 紹介先契約日
    echo $row['saki_contract_date'] . ",";
    // 紹介元会員番号
    echo mb_convert_encoding($labelShop[$row['moto_contract_shop_id']], "SJIS-win", "UTF-8") . ",";
    // 紹介元会員番号
    echo $row['moto_no'] . ",";
    // 紹介元名前
    echo mb_convert_encoding($row['moto_name'], "SJIS-win", "UTF-8") . ",";
    // 紹介元＞銀行名
    echo mb_convert_encoding($row['moto_bank_name'], "SJIS-win", "UTF-8") . ",";
    // 紹介元＞支店名
    echo mb_convert_encoding($row['moto_bank_branch'], "SJIS-win", "UTF-8") . ",";
    // 紹介元＞口座種別
    echo mb_convert_encoding($labelBankAccountType[$row['moto_bank_account_type']], "SJIS-win", "UTF-8") . ",";
    // 紹介元＞口座番号
    echo $row['moto_bank_account_no'] . ",";
    // 紹介元＞口座名義
    echo mb_convert_encoding(mb_convert_kana($row['moto_bank_account_name'], 'ks',"UTF-8"), "SJIS-win", "UTF-8") . ",";
    // 返金額
    echo $refund_list[$row['saki_contract_course_id']] . ",";
    // 申請受付連絡日
    echo ($row['refund_request'] !== '0000-00-00')? $row['refund_request'] . "," : ',';
    // 紹介先会員番号
    echo mb_convert_encoding($row['saki_no'], "SJIS-win", "UTF-8") . ",";
    // 紹介先名前
    echo mb_convert_encoding($row['saki_name'], "SJIS", "UTF-8") . ",";
    // 初回消化日
    $sales = Get_Table_Row("sales", " WHERE del_flg = 0 AND r_times > 0 AND customer_id = '" . $GLOBALS['mysqldb']->real_escape_string($row['introducer_customer_id']) . "' ORDER BY reg_date ASC LIMIT 1");
    if ($sales) {
      echo mb_convert_encoding($sales['pay_date'], "SJIS-win", "UTF-8") . ",";
    } else {
      echo mb_convert_encoding('', "SJIS-win", "UTF-8") . ",";
    }
    
    if ( $row['moto_bank_status'] != null && $row['moto_bank_status'] >= 0 ) {
      echo mb_convert_encoding($labelBankState[$row['moto_bank_status']], "SJIS-win", "UTF-8") . ",";
    } else {
      echo mb_convert_encoding("登録なし", "SJIS-win", "UTF-8") . ",";
    }
    if ( $row['saki_contract_r_times'] !== null && $row['saki_contract_status'] !== null && $row['moto_contract_status']  !== null &&
         $row['saki_contract_r_times'] > 0 && $row['moto_contract_r_times'] > 0 && !preg_match("/^[1236]{1}$/", $row['saki_contract_status']) && !preg_match("/^[1236]{1}$/", $row['moto_contract_status'])) {
       echo mb_convert_encoding("対象", "SJIS-win", "UTF-8") . ",";
    } elseif ( $row['saki_contract_r_times'] !== null && $row['saki_contract_status'] !== null && $row['moto_contract_status']  !== null &&
               ($row['saki_contract_r_times'] == 0 || $row['moto_contract_r_times'] == 0) && !preg_match("/^[1236]{1}$/", $row['saki_contract_status']) && !preg_match("/^[1236]{1}$/", $row['moto_contract_status'])) {
       echo mb_convert_encoding("対象外（施術なし）", "SJIS-win", "UTF-8") . ",";
    } elseif ( $row['saki_contract_r_times'] == null || $row['saki_contract_status'] == null || $row['moto_contract_r_times'] == null || $row['moto_contract_status'] == null || 
               preg_match("/^[1236]{1}$/", $row['moto_contract_status']) || preg_match("/^[1236]{1}$/", $row['saki_contract_status'])) {
               echo mb_convert_encoding("対象外（退会済み）", "SJIS-win", "UTF-8") . ",";
    }
    echo "\n";
  }

  //CSV Export Log 
  setCSVExportLog($_POST['csv_pw'], $filename);

}
?>