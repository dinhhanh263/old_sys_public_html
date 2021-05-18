<?php
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';

/**
*
* 割賦・請求代行状況の取得(サーバー間通信式) サンプルプログラム
*
* Date: 2017.12.12
*
* ※ このプログラムは Shift_JIS で記述されているものとします。
*
**/

/**
* 設定項目
*/

// テスト用
if (strstr( $_SERVER['SCRIPT_FILENAME'],"demo")) {
	// アクセス先を指定
	define('RYFETY_CONNECT_API_URL', 'https://reg18.smp.ne.jp/regist/is?SMPFORM=mclh-pfnfo-91672a855ffdf9f675a59566f7e374ad' );

	// ライフティから発行された加盟店APIパスワード
	define('MERCHANT_API_PASSWORD', '0fbf03afbdbf6201e902bef33cac4d8e24a583accea0d03096d36876765880cd');

	// 支店ID
	$branchId = '004754076002';

// 本番用
}else{
	// アクセス先を指定
	define('RYFETY_CONNECT_API_URL', 'https://reg18.smp.ne.jp/regist/is?SMPFORM=meoj-pfnfr-94285a90d4d60cc191f318f191b2c333' );

	// ライフティから発行された加盟店APIパスワード
	define('MERCHANT_API_PASSWORD', '5144a87ee4389b5d6ae742c4c92b6f30b1c2ca91a8be0b30546e41d4a693ac3f');

	// 支店ID
	$branchId = $shop['ryfety_id'];
}

// ライフティから発行された加盟店ID
define('MERCHANT_ID', '00475');

// $requestCustomerCodesAll = Get_Table_Array("loan_info2","id"," WHERE del_flg=0 AND apl_id<>0 AND id>5 AND regist_category>=2 AND shop_cancel=0 AND eval_status<>5 AND sum_up=0");
$requestCustomerCodesAll = Get_Table_Array("loan_info2","id"," WHERE del_flg=0 AND apl_id<>0 AND id>5 AND regist_category>=2 AND update_end_flg=0");
$j=1;
$requestCustomerCodes = array();
foreach($requestCustomerCodesAll as $seq => $id ){
	if($seq && $seq%999==0) $j++;
	$requestCustomerCodes[$j][] = $id;
}

if($requestCustomerCodes){
	for( $k = 1; $k <= $j; $k++ ){

/**
* 送信パラメータの作成
*/
// 4. 認証情報を作成
$timestamp = date( 'ymdHis' );
$authString1 = $timestamp . MERCHANT_ID . MERCHANT_API_PASSWORD;
$authString2 = hash('sha256', $authString1, true);
$authString3 = base64_encode( $authString2 );
$authString4 = $timestamp . MERCHANT_ID . $authString3;

$arguments = array(
	'customer_cd' => $requestCustomerCodes[$k]
);
$argumentsString1 = json_encode( $arguments );
$argumentsString2 = base64_encode( $argumentsString1 );
$requestParam = array(
		// 1. バージョン
		'version' => '1',
		// 2. 文字コード指定
		'detect' => '判定',
		// 3. 要求区分
		'method' => 'application.get',
		// 4. 認証情報
		'auth' => $authString4,
		// 5. 対象指定
		'arguments' => $argumentsString2,
);

/**
* 送信処理
*/
$curl = curl_init();
curl_setopt_array(
 $curl, array(
	CURLOPT_URL => RYFETY_CONNECT_API_URL,
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => http_build_query($requestParam)
 )
);
$responseJsonString = curl_exec( $curl );
if ( curl_errno( $curl ) ) {
	$message = curl_error( $curl );
	die( "APIサーバーへの接続に失敗しました: " . $message . "\n" );
}
curl_close( $curl );

/**
* 応答データの取得
*/
$responseData = json_decode( $responseJsonString, true );
if( 0 == $responseData['code'] ) {
	// APIでの結果取得に成功した場合
	$headerCodes = $responseData['header_code'];
	$headerNames = $responseData['header_name'];
	$data = $responseData['data'];
} else {
	// APIでの結果取得に失敗した場合
	$message = $responseData['message'];
	die( "APIでの結果取得に失敗しました: " . $message . "\n" );
}

/**
* 応答データの使用
* 例として、取得データを出力します
*/
$no = 0;
$import_data = array();
foreach( $data as $record ) {
	print "************************ 取得データ ".(++$no)." ************************<br>";
	for( $i = 0; $i < count($headerCodes); $i++ ){
		$itemCode = $headerCodes[$i];
		$itemName = $headerNames[$i];
		$itemValue = $record[$i];
		print $itemName . "\t" . $itemCode . "\t" . $itemValue . "<br>";
		$import_data[$no][$itemCode] = $itemValue;
	}
	print "<br>";
}

// データ取り込み
foreach($import_data as $key => $vals){
	// 受付してある、店舗キャンセルしていない、最終更新日が前日から
	$last_update = !$vals['last_update'] ? "" : date('Y-m-d H:i:s',strtotime( str_replace(array("年","月","日","時","分","秒"), array("-","-"," ",":",":",""),$vals['last_update']) ));

	if($key && substr($last_update,0,10)>=date('Y-m-d',strtotime("-1 day"))){
		$dSql = 'UPDATE loan_info2 SET 
					tel="'.$vals['cell'].'",
					deal_date="'.(!$vals['deal_date'] ? "" : date('Y-m-d H:i:s',strtotime( str_replace(array("年","月","日","時","分","秒"), array("-","-"," ",":",":",""),$vals['deal_date']) ))).'",
					regist_category='.$vals['conter_sta'].',
					eval_status='.$vals['eval_sta'].',
					sum_up='.$vals['sum_up'].',
					re_mail_date="'.(!$vals['re_mail_date'] ? "" : date('Y-m-d H:i:s',strtotime( str_replace(array("年","月","日","時","分","秒"), array("-","-"," ",":",":",""),$vals['re_mail_date']) ))).'",
					re_re_mail_date="'.(!$vals['re_re_mail_date'] ? "" : date('Y-m-d H:i:s',strtotime( str_replace(array("年","月","日","時","分","秒"), array("-","-"," ",":",":",""),$vals['re_re_mail_date']) ))).'",
					addm_no="'.$vals['addm_no'].'",
					addm_date="'.(!$vals['addm_date'] ? "" : date('Y-m-d',strtotime( str_replace(array("年","月","日"), array("-","-",""),$vals['addm_date']) ))).'",
					memo="'.$vals['memo'].'",
					shop_cancel='.$vals['shop_cancel'].',
					own_cancel='.$vals['own_cancel'].',
					last_update="'.$last_update.'",
					update_name="'.$vals['update_name'].'"';
		if($vals['shop_cancel'] || ($vals['eval_sta']==5) || $vals['sum_up']){
			$dSql .=',update_end_flg=1';
		}
		$dSql .=' WHERE apl_id='.$vals['apl_id'];

		query( $dSql );

		echo $dSql."<br><br>";
	}
}

	}
}