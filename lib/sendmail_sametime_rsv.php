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
require_once LIB_DIR . 'KagoyaSendMail.php';

$sql = "SELECT shop_id, hope_date, hope_time, room_id,route, reg_date
FROM reservation
WHERE del_flg =0 AND type <>3 AND hope_date >= NOW( ) 
GROUP BY reg_date, shop_id, hope_date, hope_time, room_id
HAVING COUNT( id ) >1";

$list = Get_Result_Sql_Array($sql);

// 送信処理
if( $list = Get_Result_Sql_Array($sql) ){
	foreach ( $list as $key=>$data ) {
		$shop_name = Get_Table_Col("shop","name"," WHERE id = '".addslashes($data['shop_id'])."'");
		$body .= $shop_name.", ".$data['hope_date'].", ".$gTime[$data['hope_time']].", ルームID:".$data['room_id'].", ".$gRoute[$data['route']].", ".$data['reg_date']."\r\n\r\n";
	}

	// 自動送信
	mb_language("ja");
	mb_internal_encoding("UTF-8");
	$from = "キレイモチェックメール";
	$from_email = "test@kireimo.jp";
	$to = "ozaki@vielis.co.jp,makizima@vielis.co.jp,murata@vielis.co.jp,obara@vielis.co.jp,koshikawa＠vielis.co.jp,kikuchi＠vielis.co.jp,tsutsumi＠vielis.co.jp";

	$subject = '同時予約発生メール';

	// mb_send_mail($to, $subject, $body, "From:".mb_encode_mimeheader($from)."<".$from_email.">");
    $kagoya = new KagoyaSendMail();
	$kagoya->send($to, $subject, $body, "From:".mb_encode_mimeheader($from)."<".$from_email.">");
}
?>