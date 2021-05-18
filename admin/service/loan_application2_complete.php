<?php
/**
*
* 割賦・請求代行要求(リンクポイント式) サンプルプログラム
*
* Date: 2017.12.12
*
* 支払種別 1(割賦のみ) の例
*
* ※ このプログラムは Shift_JIS で記述されているものとします。
*
**/
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

$table = "loan_info2";

// 詳細を取得----------------------------------------------------------------------------
$data = array();
$customer = array();
$contract = array();
$course   = array();
$shop = array();
$staff = '';

// if($_REQUEST['loan_info2_id'])		 $data = 	 Get_Table_Row($table," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['loan_info2_id'])."'");
if ($_REQUEST['id']) {
    $data = Get_Table_Row($table, " WHERE del_flg=0 AND id = '" . addslashes($_REQUEST['id']) . "'");
}
if($data['customer_id']) $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($data['customer_id'])."'");
if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($data['contract_id'])."'");
if($contract['id']) 	 $course   = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($contract['course_id'])."'");
if($data['shop_id']) 	 $shop = 	 Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($data['shop_id'])."'");
if($data['staff_id']) 	 $staff = 	 Get_Table_Col("staff","name"," WHERE del_flg=0 AND id = '".addslashes($data['staff_id'])."'");

list($name1,$name2) = explode("　",trim(mb_convert_encoding( $customer['name'], "SJIS", "UTF-8" )));
list($kana1,$kana2) = explode("　",trim(mb_convert_encoding( $customer['name_kana'], "SJIS", "UTF-8" )));
list($zip1,$zip2) = explode("-",trim(mb_convert_encoding( $customer['zip'], "SJIS", "UTF-8" )));
$pref = mb_convert_encoding( $gPref2[$customer['pref']], "SJIS", "UTF-8" );

$shop_memo = '1.預貯金額('.$data['save_amount'].'万円)、2.副収入種別('.mb_convert_encoding($array_side_job[$data['side_job']], "SJIS", "UTF-8" ).')、3.副収入の年額('.$data['side_income'].'万円)、4.家賃負担('.mb_convert_encoding($array_payment_lent[$data['payment_lent']], "SJIS", "UTF-8" ).')';

// ライフティシステム連携へ---------------------------------------------------------------------

mb_internal_encoding('Shift_JIS');

/**
* 設定項目
*/
// テスト用
if (strstr( $_SERVER['SCRIPT_FILENAME'],"demo")) {
	// アクセス先を指定
	define('RYFETY_LINKPOINT_API_URL', 'https://reg18.smp.ne.jp/regist/is?SMPFORM=mclh-pemfn-03a01d5763f6275b5c064af08e208c20' );

	// ライフティから発行された加盟店APIパスワード
	define('MERCHANT_API_PASSWORD', '0fbf03afbdbf6201e902bef33cac4d8e24a583accea0d03096d36876765880cd');

	// 支店ID
	$branchId = '004754076002';

// 本番用
}else{
	// アクセス先を指定
	define('RYFETY_LINKPOINT_API_URL', 'https://reg18.smp.ne.jp/regist/is?SMPFORM=meoj-peohl-f9b913b5f1625a3c1448358715726f61' );

	// ライフティから発行された加盟店APIパスワード
	define('MERCHANT_API_PASSWORD', '5144a87ee4389b5d6ae742c4c92b6f30b1c2ca91a8be0b30546e41d4a693ac3f');

	// 支店ID
	$branchId = $shop['ryfety_id'];
}

// ライフティから発行された加盟店ID
define('MERCHANT_ID', '00475');

/**
* 送信パラメータの作成
*/

// 57. 処理日時
$dealDt = date('YmdHis');

// 59. シグニチャ
$signatureSource = array(
	MERCHANT_ID,
	$branchId,
	$dealDt,
	MERCHANT_API_PASSWORD
);
$signatureString1 = implode(',', $signatureSource);
$signatureString2 = hash('sha256', $signatureString1, true);
$signatureString3 = base64_encode( $signatureString2 );
$signature = $signatureString3;

// 送信パラメータ
$requestParam = array(
	// 1. バージョン
	'version' => 1,
	// 2. 文字コード判定
	'detect' => '判定',
	// 3. 加盟店ID
	'shop_id' => MERCHANT_ID,
	// 4. 加盟店支店ID
	'branch_id' => $branchId,
	// 5. 申込識別ID(ローンテーブルID)
	'customer_cd' => $data['id'],
	// 6. 担当者
	'staff_name' => mb_convert_encoding( $staff, "SJIS", "UTF-8" ),
	// 7. 商品名１
	'item1_name' => mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ),
	// 8. 数量１
	'item1_qty' => '1',
	// 9. 単価１
	'item1_price' => $contract['price'],
	// 10. 金額１
	'item1_amount' => $contract['price'],
	// 27. 販売形態
	'sale_form' => '4',
	// 28. 支払種別
	'sale_pattern' => '1',
	// 29. 役務期間（自）
	// 'service_start' => date('Ym',strtotime($data['service_start'])),
	 'service_start' => date('Ymd',strtotime($data['service_start'])),
	// 30. 役務期間（至）
	// 'service_end' => date('j',strtotime($data['service_start']))==1 ?
	//				 date('Ym',strtotime($data['service_end'])) :
	//				 date('Ym',strtotime($data['service_end'].' -1 month')),

	'service_end' => date('Ymd',strtotime($data['service_end'])),

	// 32. 商品金額合計
	'total_amount' => $contract['price'],
	// 33. 頭金（お申込金）
	'initial_payment' => ($contract['price']-$contract['payment_loan']),
	// 34. 残金
	'left_payment' => $contract['payment_loan'],
	// 37. 支払回数
	'pay_times' => $data['number_of_payments'],
	// 41. お名前（姓）
	'name1' => $name1,
	// 42. お名前（名）
	'name2' => $name2,
	// 43. フリガナ（セイ）
	'kana1' => mb_convert_kana($kana1,k).' ',
	// 44. フリガナ（メイ）
	'kana2' => mb_convert_kana($kana2,k).' ',
	// 45. 生年月日（西暦）
	'birth_year' => date('Y',strtotime($customer['birthday'])),
	// 46. 生年月日（月）
	'birth_month' => date('m',strtotime($customer['birthday'])),
	// 47. 生年月日（日）
	'birth_day' => date('d',strtotime($customer['birthday'])),
	// 48. 性別
	'gender' => ($data['sex'] ? 1 : 2),
	// 49. 郵便番号１
	'postcode1' => $zip1,
	// 50. 郵便番号2
	'postcode2' => $zip2,
	// 51. 住所１
	'addr1' => $pref,
	// 52. 住所２
	'addr2' => mb_convert_encoding( $customer['address'], "SJIS", "UTF-8" ),
	// 55. 携帯電話番号
	'cell' => $customer['tel'],
	// 56. メールアドレス
	'email' => $customer['mail'],
	// 57. 処理日時
	'deal_date' => $dealDt,
	// 58. 代理店メモ
	'shop_memo' => $shop_memo,
	// 59. シグニチャ
	'sig' => $signature
	);

/**
* フォームのHTML出力
*/
$formAction = RYFETY_LINKPOINT_API_URL;

$inputHidden = '';
foreach( $requestParam as $item => $value ) {
	$value = htmlspecialchars($value, ENT_QUOTES, 'SJIS');
	$inputHidden .= "<input type=\"hidden\" name=\"$item\" value=\"$value\" >\n";
}

header('Content-Type: text/html; charset=Shift_JIS');
print <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="Shift_JIS">
<title>割賦申込</title>
</head>
<body>
<div align="center">
<p>続けて、「次へ」ボタンを押して次の画面に進みます。</p>
<form method="post" action="$formAction">
$inputHidden
<input type="submit" name="submit" value="次へ">
</form>
</div>
</body>
</html>
HTML;
?>